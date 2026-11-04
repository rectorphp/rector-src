<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

final class KeepInnerTernaryParenthesesInConcat
{
    public function run($a, $b)
    {
        return isset($a) ? $a : ('R' . ($b == 1? '' : 'ector'));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

final class KeepInnerTernaryParenthesesInConcat
{
    public function run($a, $b)
    {
        return $a ?? ('R' . ($b == 1? '' : 'ector'));
    }
}

?>
