<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Source\SomeTraitWithProperty;

final class SkipConsumeTraitProperty
{
    use SomeTraitWithProperty;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}