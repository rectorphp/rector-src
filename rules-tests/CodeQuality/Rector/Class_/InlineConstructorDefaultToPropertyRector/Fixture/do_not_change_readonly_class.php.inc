<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

readonly class DoNotChangeReadonlyClass
{
    private string $name;

    public function __construct()
    {
        $this->name = 'John';
    }
}
