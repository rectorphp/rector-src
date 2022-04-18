<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;

final class PropertyFetchAnalyzer
{
    /**
     * @var string
     */
    private const THIS = 'this';

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator,
        private readonly AstResolver $astResolver
    ) {
    }

    public function isLocalPropertyFetch(Node $node): bool
    {
        if ($node instanceof PropertyFetch) {
            if ($node->var instanceof MethodCall) {
                return false;
            }

            return $this->nodeNameResolver->isName($node->var, self::THIS);
        }

        if ($node instanceof StaticPropertyFetch) {
            return $this->nodeNameResolver->isName($node->class, ObjectReference::SELF()->getValue());
        }

        return false;
    }

    public function isLocalPropertyFetchName(Node $node, string $desiredPropertyName): bool
    {
        if (! $this->isLocalPropertyFetch($node)) {
            return false;
        }

        /** @var PropertyFetch|StaticPropertyFetch $node */
        return $this->nodeNameResolver->isName($node->name, $desiredPropertyName);
    }

    public function containsLocalPropertyFetchName(Node $node, string $propertyName): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            $node,
            fn (Node $node): bool => $this->isLocalPropertyFetchName($node, $propertyName)
        );
    }

    public function isPropertyToSelf(PropertyFetch | StaticPropertyFetch $expr): bool
    {
        if ($expr instanceof PropertyFetch && ! $this->nodeNameResolver->isName($expr->var, self::THIS)) {
            return false;
        }

        if ($expr instanceof StaticPropertyFetch && ! $this->nodeNameResolver->isName(
            $expr->class,
            ObjectReference::SELF()->getValue()
        )) {
            return false;
        }

        $class = $this->betterNodeFinder->findParentType($expr, Class_::class);
        if (! $class instanceof Class_) {
            return false;
        }

        foreach ($class->getProperties() as $property) {
            if (! $this->nodeNameResolver->areNamesEqual($property->props[0], $expr)) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function isPropertyFetch(Node $node): bool
    {
        if ($node instanceof PropertyFetch) {
            return true;
        }

        return $node instanceof StaticPropertyFetch;
    }

    /**
     * Matches:
     * "$this->someValue = $<variableName>;"
     */
    public function isVariableAssignToThisPropertyFetch(Node $node, string $variableName): bool
    {
        if (! $node instanceof Assign) {
            return false;
        }

        if (! $node->expr instanceof Variable) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->expr, $variableName)) {
            return false;
        }

        return $this->isLocalPropertyFetch($node->var);
    }

    public function isFilledByConstructParam(Property|PropertyFetch|StaticPropertyFetch $property): bool
    {
        $class = $this->betterNodeFinder->findParentType($property, Class_::class);
        if (! $class instanceof Class_) {
            return false;
        }

        $classMethod = $class->getMethod(MethodName::CONSTRUCT);
        if (! $classMethod instanceof ClassMethod) {
            return false;
        }

        $params = $classMethod->params;
        if ($params === []) {
            return false;
        }

        $stmts = (array) $classMethod->stmts;
        if ($stmts === []) {
            return false;
        }

        /** @var string $propertyName */
        $propertyName = $property instanceof Property
            ? $this->nodeNameResolver->getName($property->props[0]->name)
            : $this->nodeNameResolver->getName($property);

        if ($property instanceof Property) {
            $kindPropertyFetch = $property->isStatic()
                ? StaticPropertyFetch::class
                : PropertyFetch::class;
        } else {
            $kindPropertyFetch = $property::class;
        }

        return $this->isParamFilledStmts($params, $stmts, $propertyName, $kindPropertyFetch);
    }

    public function isFilledViaMethodCallInConstructStmts(PropertyFetch $propertyFetch): bool
    {
        $class = $this->betterNodeFinder->findParentType($propertyFetch, Class_::class);
        if (! $class instanceof Class_) {
            return false;
        }

        $construct = $class->getMethod(MethodName::CONSTRUCT);
        if (! $construct instanceof ClassMethod) {
            return false;
        }

        /** @var MethodCall[] $methodCalls */
        $methodCalls = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped(
            $construct,
            [MethodCall::class]
        );

        foreach ($methodCalls as $methodCall) {
            if (! $methodCall->var instanceof Variable) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($methodCall->var, self::THIS)) {
                continue;
            }

            $classMethod = $this->astResolver->resolveClassMethodFromMethodCall($methodCall);
            if (! $classMethod instanceof ClassMethod) {
                continue;
            }

            $isFound = $this->isPropertyAssignFoundInClassMethod($classMethod, $propertyFetch);
            if (! $isFound) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param string[] $propertyNames
     */
    public function isLocalPropertyOfNames(Node $node, array $propertyNames): bool
    {
        if (! $this->isLocalPropertyFetch($node)) {
            return false;
        }

        /** @var PropertyFetch $node */
        return $this->nodeNameResolver->isNames($node->name, $propertyNames);
    }

    private function isPropertyAssignFoundInClassMethod(ClassMethod $classMethod, PropertyFetch $propertyFetch): bool
    {
        return (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
            $classMethod,
            function (Node $subNode) use ($propertyFetch): bool {
                if (! $subNode instanceof Assign) {
                    return false;
                }

                if (! $subNode->var instanceof PropertyFetch) {
                    return false;
                }

                return $this->nodeComparator->areNodesEqual($propertyFetch, $subNode->var);
            }
        );
    }

    /**
     * @param Param[] $params
     * @param Stmt[] $stmts
     */
    private function isParamFilledStmts(
        array $params,
        array $stmts,
        string $propertyName,
        string $kindPropertyFetch
    ): bool {
        foreach ($params as $param) {
            $paramVariable = $param->var;
            $isAssignWithParamVarName = $this->betterNodeFinder->findFirst($stmts, function (Node $node) use (
                $propertyName,
                $paramVariable,
                $kindPropertyFetch
            ): bool {
                if (! $node instanceof Assign) {
                    return false;
                }

                if ($kindPropertyFetch !== $node->var::class) {
                    return false;
                }

                if (! $this->nodeNameResolver->isName($node->var, $propertyName)) {
                    return false;
                }

                return $this->nodeComparator->areNodesEqual($node->expr, $paramVariable);
            });

            if ($isAssignWithParamVarName !== null) {
                return true;
            }
        }

        return false;
    }
}
