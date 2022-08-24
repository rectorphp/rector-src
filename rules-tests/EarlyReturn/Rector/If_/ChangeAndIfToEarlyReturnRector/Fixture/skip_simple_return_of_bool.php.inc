<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

use PhpParser\Node\Expr;
use PhpParser\Node\Scalar\String_;

final class SkipSimpleReturnOfBool
{
    public function resolve(Expr $expr)
    {
        if ($expr instanceof String_ && $expr->value === '') {
            return true;
        }

        return false;
    }
}
