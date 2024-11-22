<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;

class SkipOverrideNonEmptyMethodFromTrait
{
    use ExampleFromTrait;

    public function bar()
    {
    }
}
