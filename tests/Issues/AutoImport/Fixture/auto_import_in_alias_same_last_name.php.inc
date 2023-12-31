<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class AutoImportSameLastName
{
    public function run(\PhpParser\Node\Expr\BinaryOp\Plus $plus)
    {
    }

    public function run2(AssignPlus $plus)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class AutoImportSameLastName
{
    public function run(Plus $plus)
    {
    }

    public function run2(AssignPlus $plus)
    {
    }
}

?>
