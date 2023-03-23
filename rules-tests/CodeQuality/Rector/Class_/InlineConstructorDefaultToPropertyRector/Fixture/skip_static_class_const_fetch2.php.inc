<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class SkipStaticClassConstFetch2
{
    private $name;

    private const NUMBER = 1;

    public function __construct()
    {
        $this->name = static::NUMBER;
    }
}
