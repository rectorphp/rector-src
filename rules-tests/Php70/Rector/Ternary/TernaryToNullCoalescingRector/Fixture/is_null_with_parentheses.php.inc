<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

function isNullWithParentheses()
{
    $w = !is_null($c = $x) ? ($c = $x) : $d;
    $w = is_null($c = $x) ? $d : ($c = $x);
    $w = !is_null($c) ? $c : ($a + $b);
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

function isNullWithParentheses()
{
    $w = ($c = $x) ?? $d;
    $w = ($c = $x) ?? $d;
    $w = $c ?? ($a + $b);
}

?>
