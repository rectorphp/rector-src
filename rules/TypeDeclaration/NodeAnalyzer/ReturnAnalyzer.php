<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;

final readonly class ReturnAnalyzer
{
    public function __construct(
        private SilentVoidResolver $silentVoidResolver
    ) {
    }

    /**
     * @param Return_[] $returns
     */
    public function hasOnlyReturnWithExpr(ClassMethod|Function_ $functionLike, array $returns): bool
    {
        if ($functionLike->stmts === null) {
            return false;
        }

        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                return false;
            }
        }

        return ! $this->silentVoidResolver->hasSilentVoid($functionLike);
    }
}
