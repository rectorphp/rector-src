<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;

final class SkipInterface implements ExampleFromInterface
{
    public function foo()
    {
        return 'implements only';
    }
}
