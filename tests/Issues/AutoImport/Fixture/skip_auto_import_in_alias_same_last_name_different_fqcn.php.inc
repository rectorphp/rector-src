<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class SkipAutoImportSameLastNameDifferentFqcn
{
    public function run(\Rector\Core\Tests\Issues\AutoImport\Source\Plus $plus)
    {
    }

    public function run2(AssignPlus $plus)
    {
    }
}
