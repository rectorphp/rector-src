<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;

final readonly class ReturnAnalyzer
{
    public function __construct(private SilentVoidResolver $silentVoidResolver)
    {
    }

    public function hasClassMethodRootReturn(ClassMethod|Function_|Closure $functionLike): bool
    {
        foreach ((array) $functionLike->stmts as $stmt) {
            if ($stmt instanceof Return_) {
                return true;
            }
        }

        return ! $this->silentVoidResolver->hasSilentVoid($functionLike);
    }

    /**
     * @param Return_[] $returns
     */
    public function areExclusiveExprReturns(array $returns): bool
    {
        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                return false;
            }
        }

        return true;
    }
}
