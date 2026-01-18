<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\SomeAbstractTest;

final class SkipSetupPHPUnitAsClutter extends SomeAbstractTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $someValue = 1;
    }
}
