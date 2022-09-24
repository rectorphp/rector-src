<?php

namespace Rector\Core\Tests\Issues\AutoImportInAlias\Fixture;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class AutoImportInAliasSameLastName
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

namespace Rector\Core\Tests\Issues\AutoImportInAlias\Fixture;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class AutoImportInAliasSameLastName
{
    public function run(Plus $plus)
    {
    }

    public function run2(AssignPlus $plus)
    {
    }
}

?>
