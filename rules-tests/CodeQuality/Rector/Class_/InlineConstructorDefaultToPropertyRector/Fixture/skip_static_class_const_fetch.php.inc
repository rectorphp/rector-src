<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class SkipStaticClassConstFetch
{
    private $name;

    public function __construct()
    {
        $this->name = static::class;
    }
}
