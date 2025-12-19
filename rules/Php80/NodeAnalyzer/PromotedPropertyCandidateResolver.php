<?php

declare(strict_types=1);

namespace Rector\Php80\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Enum\ClassName;
use Rector\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\ValueObject\PropertyPromotionCandidate;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class PromotedPropertyCandidateResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator,
        private PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private PhpAttributeAnalyzer $phpAttributeAnalyzer
    ) {
    }

    /**
     * @return PropertyPromotionCandidate[]
     */
    public function resolveFromClass(
        Class_ $class,
        ClassMethod $constructClassMethod,
        bool $allowModelBasedClasses
    ): array {
        if (! $allowModelBasedClasses && $this->hasModelTypeCheck($class, ClassName::DOCTRINE_ENTITY)) {
            return [];
        }

        $propertyPromotionCandidates = [];
        foreach ($class->stmts as $classStmtPosition => $classStmt) {
            if (! $classStmt instanceof Property) {
                continue;
            }

            if (count($classStmt->props) !== 1) {
                continue;
            }

            $propertyPromotionCandidate = $this->matchPropertyPromotionCandidate(
                $classStmt,
                $constructClassMethod,
                $classStmtPosition
            );
            if (! $propertyPromotionCandidate instanceof PropertyPromotionCandidate) {
                continue;
            }

            if (! $allowModelBasedClasses && $this->hasModelTypeCheck($classStmt, ClassName::JMS_TYPE)) {
                continue;
            }

            $propertyPromotionCandidates[] = $propertyPromotionCandidate;
        }

        return $propertyPromotionCandidates;
    }

    private function hasModelTypeCheck(Class_|Property $node, string $modelType): bool
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo instanceof PhpDocInfo && $phpDocInfo->hasByAnnotationClass($modelType)) {
            return true;
        }

        return $this->phpAttributeAnalyzer->hasPhpAttribute($node, $modelType);
    }

    private function matchPropertyPromotionCandidate(
        Property $property,
        ClassMethod $constructClassMethod,
        int $propertyStmtPosition
    ): ?PropertyPromotionCandidate {
        if ($property->flags === 0) {
            return null;
        }

        $onlyProperty = $property->props[0];

        $propertyName = $this->nodeNameResolver->getName($onlyProperty);
        $firstParamAsVariable = $this->resolveFirstParamUses($constructClassMethod);

        // match property name to assign in constructor
        foreach ((array) $constructClassMethod->stmts as $assignStmtPosition => $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;

            // promoted property must use non-static property only
            if (! $assign->var instanceof PropertyFetch) {
                continue;
            }

            if (! $this->propertyFetchAnalyzer->isLocalPropertyFetchName($assign->var, $propertyName)) {
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

            if ($property->type instanceof Node
                && $matchedParam->type instanceof Node
                && ! $matchedParam->default instanceof Expr
                && ! $this->nodeComparator->areNodesEqual($matchedParam->type, $property->type)) {
                continue;
            }

            if ($this->shouldSkipParam($matchedParam, $assignedExpr, $firstParamAsVariable)) {
                continue;
            }

            return new PropertyPromotionCandidate(
                $property,
                $matchedParam,
                $propertyStmtPosition,
                $assignStmtPosition
            );
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

    /**
     * @param int[] $firstParamAsVariable
     */
    private function shouldSkipParam(
        Param $matchedParam,
        Variable $assignedVariable,
        array $firstParamAsVariable
    ): bool {
        // already promoted
        if ($matchedParam->isPromoted()) {
            return true;
        }

        return $this->isParamUsedBeforeAssign($assignedVariable, $firstParamAsVariable);
    }
}
