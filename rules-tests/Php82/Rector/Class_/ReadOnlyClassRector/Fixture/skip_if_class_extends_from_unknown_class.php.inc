<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final class SkipIfClassExtendsFromUnknownClass extends UnknownClass
{
    public function __construct(private readonly string $property)
    {
    }

    public function run()
    {
        echo $this->property;
    }
}
