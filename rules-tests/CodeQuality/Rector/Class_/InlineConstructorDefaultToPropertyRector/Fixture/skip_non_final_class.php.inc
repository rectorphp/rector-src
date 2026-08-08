<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Source\ValidatedBase;

class SkipsParentConstructor extends ValidatedBase
{
    private bool $validated = false;

    public function __construct()
    {
        $this->validate();
        $this->validated = true;
    }
}
