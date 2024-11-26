<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTraitWithAbstractMethod;

final class SkipOnlyAbstractTrait
{
    use ExampleFromTraitWithAbstractMethod;

    public function run()
    {
    }
}
