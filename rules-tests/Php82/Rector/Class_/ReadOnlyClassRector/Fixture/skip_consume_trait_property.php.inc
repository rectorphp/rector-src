<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

use Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Source\SomeTraitWithProperty;

final class SkipConsumeTraitProperty
{
    use SomeTraitWithProperty;

    public function __construct(private readonly string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
