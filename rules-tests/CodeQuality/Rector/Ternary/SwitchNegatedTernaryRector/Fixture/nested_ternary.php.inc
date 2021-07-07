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
            : [];
    }
}

?>