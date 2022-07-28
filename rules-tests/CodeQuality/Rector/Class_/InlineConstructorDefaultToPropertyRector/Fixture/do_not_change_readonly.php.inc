<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class DoNotChangeReadonly
{
    private readonly string $name;

    public function __construct()
    {
        $this->name = 'John';
    }
}
