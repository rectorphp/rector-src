<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source;

use PHPUnit\Framework\TestCase;

abstract class SomeAbstractTest extends TestCase
{
    protected function setUp(): void
    {
        $value = 1000;
    }
}
