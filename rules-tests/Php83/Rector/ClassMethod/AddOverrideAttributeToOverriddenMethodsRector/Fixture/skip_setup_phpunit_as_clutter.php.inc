<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\SomeAbstractTestCase;

final class SkipSetupPHPUnitAsClutter extends SomeAbstractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $someValue = 1;
    }
}
