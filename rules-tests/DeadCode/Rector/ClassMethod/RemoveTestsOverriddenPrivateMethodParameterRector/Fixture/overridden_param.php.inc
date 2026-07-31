<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveTestsOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class OverriddenParam extends TestCase
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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveTestsOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class OverriddenParam extends TestCase
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
