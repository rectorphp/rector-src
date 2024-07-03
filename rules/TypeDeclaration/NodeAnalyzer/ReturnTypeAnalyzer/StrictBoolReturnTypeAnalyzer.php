<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\TypeDeclaration\TypeAnalyzer\AlwaysStrictBoolExprAnalyzer;

final readonly class StrictBoolReturnTypeAnalyzer
{
    public function __construct(
        private AlwaysStrictBoolExprAnalyzer $alwaysStrictBoolExprAnalyzer,
        private AlwaysStrictReturnAnalyzer $alwaysStrictReturnAnalyzer
    ) {
    }

    public function hasAlwaysStrictBoolReturn(ClassMethod|Function_ $functionLike): bool
    {
        $returns = $this->alwaysStrictReturnAnalyzer->matchAlwaysStrictReturns($functionLike);
        if ($returns === []) {
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
