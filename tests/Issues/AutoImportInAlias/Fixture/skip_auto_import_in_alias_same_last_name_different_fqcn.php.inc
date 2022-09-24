<?php

namespace Rector\Core\Tests\Issues\AutoImportInAlias\Fixture;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class AutoImportInAliasSameLastNameDifferentFqcn
{
    public function run(\Rector\Core\Tests\Issues\AutoImportInAlias\Source\Plus $plus)
    {
    }

    public function run2(AssignPlus $plus)
    {
    }
}
