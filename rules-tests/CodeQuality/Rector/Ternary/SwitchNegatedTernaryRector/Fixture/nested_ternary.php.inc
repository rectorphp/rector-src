<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector\Fixture;

class NestedTernary
{
    function run($result)
    {
        return ! isset($result['validation_messages'])
            ? []
            : (isset($result['validation_messages']['http']) ? $result['validation_messages'] : $result);
    }

    function run2($a, $b, $c, $d, $e)
    {
        return ! $a
            ? ($c ? $d : $e)
            : $b;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector\Fixture;

class NestedTernary
{
    function run($result)
    {
        return isset($result['validation_messages'])
            ? (isset($result['validation_messages']['http']) ? $result['validation_messages'] : $result)
            : ([]);
    }

    function run2($a, $b, $c, $d, $e)
    {
        return $a
            ? ($b)
            : ($c ? $d : $e);
    }
}

?>
