<?php

namespace Rector\Core\Tests\Issues\AliasedImportDouble\Fixture;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

final class TernaryReturn2
{
    public function run(?Expr $expr, Expr $expr2): BooleanOr
    {
        return $expr ?: new BooleanOr($expr2, $expr2);
    }
}

?>
