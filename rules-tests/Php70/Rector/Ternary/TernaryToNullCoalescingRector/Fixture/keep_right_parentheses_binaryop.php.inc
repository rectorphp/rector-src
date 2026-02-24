<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

class KeepRightParenthesesBinaryOp
{
    public function run($bar, $baz, $qux)
    {
        $foo = isset($bar) ? $bar : ($baz && $qux);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

class KeepRightParenthesesBinaryOp
{
    public function run($bar, $baz, $qux)
    {
        $foo = $bar ?? ($baz && $qux);
    }
}

?>
