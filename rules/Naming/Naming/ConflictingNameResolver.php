<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\NodeManipulator\FunctionLikeManipulator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver;
use Rector\Naming\PhpArray\ArrayFilter;

final class ConflictingNameResolver
{
    /**
     * @var array<int, string[]>
     */
    private array $conflictingVariableNamesByClassMethod = [];

    public function __construct(
        private readonly ArrayFilter $arrayFilter,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ExpectedNameResolver $expectedNameResolver,
        private readonly MatchParamTypeExpectedNameResolver $matchParamTypeExpectedNameResolver,
        private readonly FunctionLikeManipulator $functionLikeManipulator
    ) {
    }

    /**
     * @return string[]
     */
    public function resolveConflictingVariableNamesForParam(
        ClassMethod|Function_|Closure|ArrowFunction $classMethod
    ): array {
        $expectedNames = [];
        foreach ($classMethod->params as $param) {
            $expectedName = $this->matchParamTypeExpectedNameResolver->resolve($param);
            if ($expectedName === null) {
                continue;
            }

            $expectedNames[] = $expectedName;
        }

        return $this->arrayFilter->filterWithAtLeastTwoOccurences($expectedNames);
    }

    public function hasNameIsInFunctionLike(
        string $variableName,
        ClassMethod | Function_ | Closure | ArrowFunction $functionLike
    ): bool {
        $conflictingVariableNames = $this->resolveConflictingVariableNamesForNew($functionLike);
        return in_array($variableName, $conflictingVariableNames, true);
    }

    /**
     * @return string[]
     */
    private function resolveConflictingVariableNamesForNew(
        ClassMethod | Function_ | Closure | ArrowFunction $functionLike
    ): array {
        // cache it!
        $classMethodId = spl_object_id($functionLike);

        if (isset($this->conflictingVariableNamesByClassMethod[$classMethodId])) {
            return $this->conflictingVariableNamesByClassMethod[$classMethodId];
        }

        $paramNames = $this->functionLikeManipulator->resolveParamNames($functionLike);
        $newAssignNames = $this->resolveForNewAssigns($functionLike);
        $nonNewAssignNames = $this->resolveForNonNewAssigns($functionLike);

        $protectedNames = array_merge($paramNames, $newAssignNames, $nonNewAssignNames);

        $protectedNames = $this->arrayFilter->filterWithAtLeastTwoOccurences($protectedNames);
        $this->conflictingVariableNamesByClassMethod[$classMethodId] = $protectedNames;

        return $protectedNames;
    }

    /**
     * @return string[]
     */
    private function resolveForNewAssigns(ClassMethod | Function_ | Closure | ArrowFunction $functionLike): array
    {
        $names = [];

        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstanceOf((array) $functionLike->getStmts(), Assign::class);
        foreach ($assigns as $assign) {
            $name = $this->expectedNameResolver->resolveForAssignNew($assign);
            if ($name === null) {
                continue;
            }

            $names[] = $name;
        }

        return $names;
    }

    /**
     * @return string[]
     */
    private function resolveForNonNewAssigns(ClassMethod | Function_ | Closure | ArrowFunction $functionLike): array
    {
        $names = [];

        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstanceOf((array) $functionLike->getStmts(), Assign::class);
        foreach ($assigns as $assign) {
            $name = $this->expectedNameResolver->resolveForAssignNonNew($assign);
            if ($name === null) {
                continue;
            }

            $names[] = $name;
        }

        return $names;
    }
}
