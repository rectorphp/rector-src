<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

class KeepRightParentheses
{
    public function run($port, $scheme)
    {
        $port = isset($port) ? $port : (($scheme == 'https') ? '443' : '80');
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Ternary\TernaryToNullCoalescingRector\Fixture;

class KeepRightParentheses
{
    public function run($port, $scheme)
    {
        $port = $port ?? (($scheme == 'https') ? '443' : '80');
    }
}

?>
