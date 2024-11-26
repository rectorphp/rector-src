<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;

final class SkipOverrideOnlyTraitWithNonEmptyMethod
{
    use ExampleFromTrait;

    public function run()
    {
    }
}
