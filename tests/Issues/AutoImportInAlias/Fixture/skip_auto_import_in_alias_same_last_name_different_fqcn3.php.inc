<?php

namespace Rector\Core\Tests\Issues\AutoImportInAlias\Fixture;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class SkipAutoImportInAliasSameLastNameDifferentFqcn2
{
    public function run(\Plus $plus)
    {
    }

    public function run2(\AssignPlus $plus)
    {
    }
}
