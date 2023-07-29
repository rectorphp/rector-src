<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeFinder;

use PHPStan\Type\StaticType;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class PropertyFetchFinder
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly AstResolver $astResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    /**
     * @return array<PropertyFetch|StaticPropertyFetch>
     */
    public function findPrivatePropertyFetches(Class_ $class, Property | Param $propertyOrPromotedParam, Scope $scope): array
    {
        $propertyName = $this->resolvePropertyName($propertyOrPromotedParam);
        if ($propertyName === null) {
            return [];
        }

        $classReflection = $this->reflectionResolver->resolveClassAndAnonymousClass($class);

        $nodes = [$class];
        $nodesTrait = $this->astResolver->parseClassReflectionTraits($classReflection);
        $hasTrait = $nodesTrait !== [];
        $nodes = array_merge($nodes, $nodesTrait);

        return $this->findPropertyFetchesInClassLike($class, $nodes, $propertyName, $hasTrait, $scope);
    }

    /**
     * @return PropertyFetch[]|StaticPropertyFetch[]
     */
    public function findLocalPropertyFetchesByName(Class_ $class, string $paramName): array
    {
        /** @var PropertyFetch[]|StaticPropertyFetch[] $foundPropertyFetches */
        $foundPropertyFetches = $this->betterNodeFinder->find(
            $class->getMethods(),
            function (Node $subNode) use ($paramName): bool {
                if ($subNode instanceof PropertyFetch) {
                    return $this->propertyFetchAnalyzer->isLocalPropertyFetchName($subNode, $paramName);
                }

                if ($subNode instanceof StaticPropertyFetch) {
                    return $this->propertyFetchAnalyzer->isLocalPropertyFetchName($subNode, $paramName);
                }

                return false;
            }
        );

        return $foundPropertyFetches;
    }

    /**
     * @return ArrayDimFetch[]
     */
    public function findLocalPropertyArrayDimFetchesAssignsByName(Class_ $class, Property $property): array
    {
        $propertyName = $this->nodeNameResolver->getName($property);
        /** @var ArrayDimFetch[] $propertyArrayDimFetches */
        $propertyArrayDimFetches = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $class->getMethods(),
            function (Node $subNode) use (&$propertyArrayDimFetches, $propertyName) {
                if (! $subNode instanceof Assign) {
                    return null;
                }

                if (! $subNode->var instanceof ArrayDimFetch) {
                    return null;
                }

                $dimFetchVar = $subNode->var;

                if (! $dimFetchVar->var instanceof PropertyFetch && ! $dimFetchVar->var instanceof StaticPropertyFetch) {
                    return null;
                }

                if (! $this->propertyFetchAnalyzer->isLocalPropertyFetchName($dimFetchVar->var, $propertyName)) {
                    return null;
                }

                $propertyArrayDimFetches[] = $dimFetchVar;
                return null;
            }
        );

        return $propertyArrayDimFetches;
    }

    public function isLocalPropertyFetchByName(Expr $expr, Class_|Trait_ $class, string $propertyName): bool
    {
        if (! $expr instanceof PropertyFetch) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($expr->name, $propertyName)) {
            return false;
        }

        if ($this->nodeNameResolver->isName($expr->var, 'this')) {
            return true;
        }

        $type = $this->nodeTypeResolver->getType($expr->var);
        if ($type instanceof StaticType || $type instanceof FullyQualifiedObjectType) {
            return $this->nodeNameResolver->isName($class, $type->getClassName());
        }

        return false;
    }

    /**
     * @param Stmt[] $stmts
     * @return PropertyFetch[]|StaticPropertyFetch[]
     */
    private function findPropertyFetchesInClassLike(
        Class_|Trait_ $class,
        array $stmts,
        string $propertyName,
        bool $hasTrait,
        Scope $scope
    ): array {
        /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->find(
            $stmts,
            function (Node $subNode) use ($class, $hasTrait, $propertyName, $scope): bool {
                if ($subNode instanceof MethodCall || $subNode instanceof StaticCall) {
                    $this->decoratePropertyFetch($subNode, $scope);
                    return false;
                }

                if ($subNode instanceof PropertyFetch) {
                    if ($this->isInAnonymous($subNode, $class, $hasTrait)) {
                        return false;
                    }

                    return $this->isNamePropertyNameEquals($subNode, $propertyName, $class);
                }

                if ($subNode instanceof StaticPropertyFetch) {
                    return $this->nodeNameResolver->isName($subNode->name, $propertyName);
                }

                return false;
            }
        );

        return $propertyFetches;
    }

    private function decoratePropertyFetch(Node $node, Scope $scope): void
    {
        if (! $node instanceof MethodCall && ! $node instanceof StaticCall) {
            return;
        }

        if ($node->isFirstClassCallable()) {
            return;
        }

        foreach ($node->getArgs() as $key => $arg) {
            if (!$arg->value instanceof PropertyFetch && !$arg->value instanceof StaticPropertyFetch) {
                continue;
            }

            if (!$this->isFoundByRefParam($node, $key, $scope)) {
                continue;
            }

            $arg->value->setAttribute(AttributeKey::IS_USED_AS_ARG_BY_REF_VALUE, true);
        }
    }

    private function isFoundByRefParam(MethodCall | StaticCall $node, int $key, Scope $scope): bool
    {
        $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
        if ($functionLikeReflection === null) {
            return false;
        }

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select(
            $functionLikeReflection,
            $node,
            $scope
        );

        $parameters = $parametersAcceptor->getParameters();
        if (! isset($parameters[$key])) {
            return false;
        }

        return $parameters[$key]->passedByReference()->yes();
    }

    private function isInAnonymous(PropertyFetch $propertyFetch, Class_|Trait_ $class, bool $hasTrait): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($propertyFetch);
        if (! $classReflection instanceof ClassReflection || ! $classReflection->isClass()) {
            return false;
        }

        if ($classReflection->getName() === $this->nodeNameResolver->getName($class)) {
            return false;
        }

        return ! $hasTrait;
    }

    private function isNamePropertyNameEquals(
        PropertyFetch $propertyFetch,
        string $propertyName,
        Class_|Trait_ $class
    ): bool {
        // early check if property fetch name is not equals with property name
        // so next check is check var name and var type only
        if (! $this->isLocalPropertyFetchByName($propertyFetch, $class, $propertyName)) {
            return false;
        }

        $propertyFetchVarType = $this->nodeTypeResolver->getType($propertyFetch->var);
        if (! $propertyFetchVarType instanceof TypeWithClassName) {
            return false;
        }

        $propertyFetchVarTypeClassName = $propertyFetchVarType->getClassName();
        $classLikeName = $this->nodeNameResolver->getName($class);

        return $propertyFetchVarTypeClassName === $classLikeName;
    }

    private function resolvePropertyName(Property | Param $propertyOrPromotedParam): ?string
    {
        if ($propertyOrPromotedParam instanceof Property) {
            return $this->nodeNameResolver->getName($propertyOrPromotedParam->props[0]);
        }

        return $this->nodeNameResolver->getName($propertyOrPromotedParam->var);
    }
}
