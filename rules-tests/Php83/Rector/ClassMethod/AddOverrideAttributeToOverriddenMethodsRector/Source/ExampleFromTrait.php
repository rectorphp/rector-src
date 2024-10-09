<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source;

trait ExampleFromTrait
{
    public abstract function foo();

    public function bar() {

    }
}
