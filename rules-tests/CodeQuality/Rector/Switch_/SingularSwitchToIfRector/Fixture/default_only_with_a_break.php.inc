<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class DefaultOnly
{
    public function run($value)
    {
        $result = 1;
        switch ($value) {
            default:
                $result = 1000;
                break;
        }

        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class DefaultOnly
{
    public function run($value)
    {
        $result = 1;
        $result = 1000;

        return $result;
    }
}

?>
