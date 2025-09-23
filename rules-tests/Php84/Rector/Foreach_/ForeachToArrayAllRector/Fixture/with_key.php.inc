<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class WithKey
{
    public function run(array $animals, array $params)
    {
        $match = true;
        foreach ($animals as $k => $v) {
            if (!isset($params[$k]) || (string) $params[$k] !== (string) $v) {
                $match = false;
                break;
            }
        }
        return $match;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class WithKey
{
    public function run(array $animals, array $params)
    {
        $match = array_all($animals, fn($v, $k) => !(!isset($params[$k]) || (string) $params[$k] !== (string) $v));
        return $match;
    }
}

?>
