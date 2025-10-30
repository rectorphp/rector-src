<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnArrayDocblockFromDataProviderParamRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SkipIterator extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething(string $name)
    {
    }

    public static function provideData(): \Iterator
    {
        yield ['first', 'second'];
    }
}
