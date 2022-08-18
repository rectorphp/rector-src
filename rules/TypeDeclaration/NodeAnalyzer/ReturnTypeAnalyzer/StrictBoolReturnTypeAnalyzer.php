<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\TypeDeclaration\TypeAnalyzer\AlwaysStrictBoolExprAnalyzer;

final class StrictBoolReturnTypeAnalyzer
{
    public function __construct(
        private readonly AlwaysStrictBoolExprAnalyzer $alwaysStrictBoolExprAnalyzer,
        private readonly AlwaysStrictReturnAnalyzer $alwaysStrictReturnAnalyzer
    ) {
    }

    public function hasAlwaysStrictBoolReturn(ClassMethod|Closure|Function_ $functionLike): bool
    {
        $returns = $this->alwaysStrictReturnAnalyzer->matchAlwaysStrictReturns($functionLike);
        if ($returns === null) {
            return false;
        }

        foreach ($returns as $return) {
            // we need exact expr return
            if (! $return->expr instanceof Expr) {
                return false;
            }

            if (! $this->alwaysStrictBoolExprAnalyzer->isStrictBoolExpr($return->expr)) {
                return false;
            }
        }

        return true;
    }
}
