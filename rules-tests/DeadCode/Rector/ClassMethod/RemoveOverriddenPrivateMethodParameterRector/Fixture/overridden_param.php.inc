<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class OverriddenParam
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        $value = 500;

        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class OverriddenParam
{
    public function run()
    {
        return $this->create();
    }

    private function create()
    {
        $value = 500;
        return $value;
    }
}

?>
