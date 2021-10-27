<?php

namespace Rector\Tests\DowngradePhp74\Rector\ClassMethod\DowngradeCovariantReturnTypeRector\Fixture;

abstract class AParent
{
    public function run(): self
    {
        return $this;
    }
}

class AChildClass extends AParent
{
    public function run(): parent
    {
        return $this;
    }
}
