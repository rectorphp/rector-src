<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source;

use PHPUnit\Framework\TestCase;

abstract class SomeAbstractTestCase extends TestCase
{
    protected function setUp(): void
    {
        $value = 1000;
    }
}
