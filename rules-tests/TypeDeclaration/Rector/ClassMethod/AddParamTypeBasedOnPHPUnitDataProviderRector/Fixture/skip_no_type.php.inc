<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class SkipNoType extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test($name)
    {
    }

    public function provideData($some): Iterator
    {
        yield [$some];
    }
}
