<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use PhpParser\Node;

final class IncludeAnd
{
    public function go(Node $var)
    {
        if ($var instanceof Node && $var->getLine() === 100) {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use PhpParser\Node;

final class IncludeAnd
{
    public function go(Node $var)
    {
        if ($var->getLine() === 100) {
        }
    }
}

?>
