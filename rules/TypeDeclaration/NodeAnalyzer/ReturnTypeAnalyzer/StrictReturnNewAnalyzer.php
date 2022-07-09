<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;

final class StrictReturnNewAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function matchAlwaysReturnVariableNew(ClassMethod|Closure|Function_ $functionLike): ?string
    {
        if ($functionLike->stmts === null) {
            return null;
        }

        if ($this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped($functionLike, [Yield_::class])) {
            return null;
        }

        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Return_::class);
        if ($returns === []) {
            return null;
        }

        // is one statement depth 3?
        if (! $this->areExclusiveExprReturns($returns)) {
            return null;
        }

        // has root return?
        if (! $this->hasClassMethodRootReturn($functionLike)) {
            return null;
        }

        if (count($returns) !== 1) {
            return null;
        }

        // exact one return of variable
        $onlyReturn = $returns[0];
        if (! $onlyReturn->expr instanceof Variable) {
            return null;
        }

        $createdVariablesToTypes = $this->resolveCreatedVariablesToTypes($functionLike);

        $returnedVariableName = $this->nodeNameResolver->getName($onlyReturn->expr);

        return $createdVariablesToTypes[$returnedVariableName] ?? null;
    }

    /**
     * @param Return_[] $returns
     */
    private function areExclusiveExprReturns(array $returns): bool
    {
        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                return false;
            }
        }

        return true;
    }

    private function hasClassMethodRootReturn(ClassMethod|Function_|Closure $functionLike): bool
    {
        foreach ((array) $functionLike->stmts as $stmt) {
            if ($stmt instanceof Return_) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, string>
     */
    private function resolveCreatedVariablesToTypes(ClassMethod|Function_|Closure $functionLike): array
    {
        $createdVariablesToTypes = [];

        // what new is assigned to it?
        foreach ((array) $functionLike->stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;
            if (! $assign->expr instanceof New_) {
                continue;
            }

            if (! $assign->var instanceof Variable) {
                continue;
            }

            $variableName = $this->nodeNameResolver->getName($assign->var);
            if (! is_string($variableName)) {
                continue;
            }

            $className = $this->nodeNameResolver->getName($assign->expr->class);
            if (! is_string($className)) {
                continue;
            }

            $createdVariablesToTypes[$variableName] = $className;
        }

        return $createdVariablesToTypes;
    }
}
