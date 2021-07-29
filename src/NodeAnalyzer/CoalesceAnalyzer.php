<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;

final class CoalesceAnalyzer
{
    /**
     * @var string[]
     */
    private const ISSETABLE_EXPR = [
        Variable::class,
        ArrayDimFetch::class,
        PropertyFetch::class,
        StaticPropertyFetch::class,
    ];

    public function hasIssetableLeft(Coalesce $coalesce): bool
    {
        $leftClass = $coalesce->left::class;
        foreach (self::ISSETABLE_EXPR as $issetableExpr) {
            if ($leftClass === $issetableExpr) {
                return true;
            }
        }

        return false;
    }
}
