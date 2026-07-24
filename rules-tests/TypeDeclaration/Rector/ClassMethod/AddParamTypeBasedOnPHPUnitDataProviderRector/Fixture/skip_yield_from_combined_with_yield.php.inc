<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipYieldFromCombinedWithYield extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function test($value): void
    {
    }

    public static function provideData(): iterable
    {
        yield from self::provideMoreData();
        yield ['name'];
    }

    public static function provideMoreData(): iterable
    {
        yield [100];
    }
}

?>
