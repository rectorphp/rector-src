<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class SkipDynamicNegativePositive
{
    private $propertyA;
    private $propertyB;

    public function __construct()
    {
        $this->propertyA = -$this->init();
        $this->propertyB = +$this->init();
    }

    private function init()
    {
        return 1;
    }
}
