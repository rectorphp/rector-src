<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class SkipTypedProperty
{
    private ?int $age;

    public function run()
    {
        return isset($this->age);
    }
}
