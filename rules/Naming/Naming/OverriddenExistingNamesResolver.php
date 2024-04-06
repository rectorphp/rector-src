<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\Naming\PhpArray\ArrayFilter;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\Node\BetterNodeFinder;

final class OverriddenExistingNamesResolver
{
    /**
     * @var array<int, array<int, string>>
     */
    private array $overriddenExistingVariableNamesByClassMethod = [];

    public function __construct(
        private readonly ArrayFilter $arrayFilter,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function hasNameInClassMethodForNew(
        string $variableName,
        ClassMethod | Function_ | Closure $functionLike
    ): bool {
        $overriddenVariableNames = $this->resolveOverriddenNamesForNew($functionLike);
        return in_array($variableName, $overriddenVariableNames, true);
    }

    public function hasNameInFunctionLikeForParam(
        string $expectedName,
        ClassMethod|Function_|Closure|ArrowFunction $classMethod
    ): bool {
        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstanceOf((array) $classMethod->getStmts(), Assign::class);

        $usedVariableNames = [];
        foreach ($assigns as $assign) {
            if (! $assign->var instanceof Variable) {
                continue;
            }

            $variableName = $this->nodeNameResolver->getName($assign->var);
            if ($variableName === null) {
                continue;
            }

            $usedVariableNames[] = $variableName;
        }

        return in_array($expectedName, $usedVariableNames, true);
    }

    /**
     * @return string[]
     */
    private function resolveOverriddenNamesForNew(ClassMethod | Function_ | Closure $functionLike): array
    {
        $classMethodId = spl_object_id($functionLike);

        if (isset($this->overriddenExistingVariableNamesByClassMethod[$classMethodId])) {
            return $this->overriddenExistingVariableNamesByClassMethod[$classMethodId];
        }

        $currentlyUsedNames = [];

        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstanceOf((array) $functionLike->stmts, Assign::class);

        foreach ($assigns as $assign) {
            /** @var Variable $assignVariable */
            $assignVariable = $assign->var;
            $currentVariableName = $this->nodeNameResolver->getName($assignVariable);
            if ($currentVariableName === null) {
                continue;
            }

            $currentlyUsedNames[] = $currentVariableName;
        }

        $currentlyUsedNames = array_values($currentlyUsedNames);
        $currentlyUsedNames = $this->arrayFilter->filterWithAtLeastTwoOccurrences($currentlyUsedNames);

        $this->overriddenExistingVariableNamesByClassMethod[$classMethodId] = $currentlyUsedNames;

        return $currentlyUsedNames;
    }
}
