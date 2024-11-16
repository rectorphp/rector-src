<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;

final class SkipAbstractTraitMethod
{
    use ExampleFromTrait;

    public function foo()
    {
    }
}
