<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class EarlyReturnWithKey
{
    public function runWithKey(array $animals, array $params)
    {
        foreach ($animals as $k => $v) {
            if (!isset($params[$k]) || (string) $params[$k] !== (string) $v) {
                return false;
            }
        }
        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class EarlyReturnWithKey
{
    public function runWithKey(array $animals, array $params)
    {
        return array_all($animals, fn($v, $k) => !(!isset($params[$k]) || (string) $params[$k] !== (string) $v));
    }
}

?>
