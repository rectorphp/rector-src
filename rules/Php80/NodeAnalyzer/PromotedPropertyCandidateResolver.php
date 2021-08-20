<?php

declare(strict_types=1);

namespace Rector\Php80\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\Php80\ValueObject\PropertyPromotionCandidate;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

final class PromotedPropertyCandidateResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator,
        private PropertyTypeInferer $propertyTypeInferer,
        private NodeTypeResolver $nodeTypeResolver,
        private TypeComparator $typeComparator,
        private TypeFactory $typeFactory
    ) {
    }

    /**
     * @return PropertyPromotionCandidate[]
     */
    public function resolveFromClass(Class_ $class): array
    {
        $constructClassMethod = $class->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            return [];
        }

        $propertyPromotionCandidates = [];
        foreach ($class->getProperties() as $property) {
            $propertyCount = count($property->props);
            if ($propertyCount !== 1) {
                continue;
            }

            $propertyPromotionCandidate = $this->matchPropertyPromotionCandidate($property, $constructClassMethod);
            if (! $propertyPromotionCandidate instanceof PropertyPromotionCandidate) {
                continue;
            }

            $propertyPromotionCandidates[] = $propertyPromotionCandidate;
        }

        return $propertyPromotionCandidates;
    }

    private function matchPropertyPromotionCandidate(
        Property $property,
        ClassMethod $constructClassMethod
    ): ?PropertyPromotionCandidate {
        $onlyProperty = $property->props[0];

        $propertyName = $this->nodeNameResolver->getName($onlyProperty);
        $firstParamAsVariable = $this->resolveFirstParamUses($constructClassMethod);

        // match property name to assign in constructor
        foreach ((array) $constructClassMethod->stmts as $stmt) {
            if ($stmt instanceof Expression) {
                $stmt = $stmt->expr;
            }

            if (! $stmt instanceof Assign) {
                continue;
            }

            $assign = $stmt;
            if (! $this->nodeNameResolver->isLocalPropertyFetchNamed($assign->var, $propertyName)) {
                continue;
            }

            // 1. is param
            $assignedExpr = $assign->expr;
            if (! $assignedExpr instanceof Variable) {
                continue;
            }

            $matchedParam = $this->matchClassMethodParamByAssignedVariable($constructClassMethod, $assignedExpr);
            if (! $matchedParam instanceof Param) {
                continue;
            }

            if ($this->shouldSkipParam($matchedParam, $property, $assignedExpr, $firstParamAsVariable)) {
                continue;
            }

            return new PropertyPromotionCandidate($property, $assign, $matchedParam);
        }

        return null;
    }

    /**
     * @return array<string, int>
     */
    private function resolveFirstParamUses(ClassMethod $classMethod): array
    {
        $paramByFirstUsage = [];
        foreach ($classMethod->params as $param) {
            $paramName = $this->nodeNameResolver->getName($param);

            $firstParamVariable = $this->betterNodeFinder->findFirst((array) $classMethod->stmts, function (Node $node) use (
                $paramName
            ): bool {
                if (! $node instanceof Variable) {
                    return false;
                }

                return $this->nodeNameResolver->isName($node, $paramName);
            });

            if (! $firstParamVariable instanceof Node) {
                continue;
            }

            $paramByFirstUsage[$paramName] = $firstParamVariable->getStartTokenPos();
        }

        return $paramByFirstUsage;
    }

    private function matchClassMethodParamByAssignedVariable(
        ClassMethod $classMethod,
        Variable $variable
    ): ?Param {
        foreach ($classMethod->params as $param) {
            if (! $this->nodeComparator->areNodesEqual($variable, $param->var)) {
                continue;
            }

            return $param;
        }

        return null;
    }

    /**
     * @param array<string, int> $firstParamAsVariable
     */
    private function isParamUsedBeforeAssign(Variable $variable, array $firstParamAsVariable): bool
    {
        $variableName = $this->nodeNameResolver->getName($variable);

        $firstVariablePosition = $firstParamAsVariable[$variableName] ?? null;
        if ($firstVariablePosition === null) {
            return false;
        }

        return $firstVariablePosition < $variable->getStartTokenPos();
    }

    private function hasConflictingParamType(Param $param, Type $propertyType): bool
    {
        if ($param->type === null) {
            return false;
        }

        $matchedParamType = $this->nodeTypeResolver->resolve($param);
        if ($param->default !== null) {
            $defaultValueType = $this->nodeTypeResolver->getStaticType($param->default);
            $matchedParamType = $this->typeFactory->createMixedPassedOrUnionType(
                [$matchedParamType, $defaultValueType]
            );
        }

        $isAllFullyQualifiedObjectType = true;
        if ($propertyType instanceof UnionType) {
            foreach ($propertyType->getTypes() as $type) {
                if (! $type instanceof FullyQualifiedObjectType) {
                    $isAllFullyQualifiedObjectType = false;
                    break;
                }
            }
        }

        // different types, not a good to fit
        return ! $isAllFullyQualifiedObjectType && ! $this->typeComparator->areTypesEqual($propertyType, $matchedParamType);
    }

    /**
     * @param int[] $firstParamAsVariable
     */
    private function shouldSkipParam(
        Param $matchedParam,
        Property $property,
        Variable $assignedVariable,
        array $firstParamAsVariable
    ): bool {
        // already promoted
        if ($matchedParam->flags !== 0) {
            return true;
        }

        // @todo unknown type, not suitable?
        $propertyType = $this->propertyTypeInferer->inferProperty($property);
        if ($this->hasConflictingParamType($matchedParam, $propertyType)) {
            return true;
        }

        return $this->isParamUsedBeforeAssign($assignedVariable, $firstParamAsVariable);
    }
}
