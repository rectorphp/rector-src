<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SkipAlreadySetType extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return \Generator<array<int, array{0: string, 1: string}>>
     */
    public static function provideData(): \Generator
    {
        yield ['data1', 'data2'];
        yield ['item4', 'item5'];
    }

    #[DataProvider('provideDataNext')]
    public function testSomethingElse()
    {
    }

    /**
     * @return \Iterator<array<int, array{0: string, 1: string}>>
     */
    public static function provideDataNext(): \Iterator
    {
        yield ['data1', 'data2'];
        yield ['item4', 'item5'];
    }
}
