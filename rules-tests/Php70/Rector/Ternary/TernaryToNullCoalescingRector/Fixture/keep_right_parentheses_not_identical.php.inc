<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

class KeepRightParenthesesNotIdentical
{
    public function run($a, $b)
    {
        $first = ($a !== null) ? $a : ($b ? 'yes' : 'no');
        $second = null === $a ? ($b ? 'yes' : 'no') : $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

class KeepRightParenthesesNotIdentical
{
    public function run($a, $b)
    {
        $first = $a ?? ($b ? 'yes' : 'no');
        $second = $a ?? ($b ? 'yes' : 'no');
    }
}

?>
