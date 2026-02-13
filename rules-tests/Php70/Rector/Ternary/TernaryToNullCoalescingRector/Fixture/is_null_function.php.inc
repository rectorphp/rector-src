<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

function ternaryWithIsNull()
{
    $x = is_null($value) ? 10 : $value;

    $y = is_null($a) ? $b : $a;

    $z = !is_null($value) ? $value : 10;

    $w = !is_null($c) ? $c : $d;
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

function ternaryWithIsNull()
{
    $x = $value ?? 10;

    $y = $a ?? $b;

    $z = $value ?? 10;

    $w = $c ?? $d;
}

?>
