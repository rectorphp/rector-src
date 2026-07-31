<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class OverriddenInBetweenParam
{
    public function run()
    {
        return $this->create(1, 2, 3);
    }

    private function create($first, $second, $third)
    {
        $second = 100;

        return [$first, $second, $third];
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class OverriddenInBetweenParam
{
    public function run()
    {
        return $this->create(1, 3);
    }

    private function create($first, $third)
    {
        $second = 100;

        return [$first, $second, $third];
    }
}

?>
