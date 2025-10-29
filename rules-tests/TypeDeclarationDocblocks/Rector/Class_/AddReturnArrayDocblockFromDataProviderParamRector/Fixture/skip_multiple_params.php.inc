<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnArrayDocblockFromDataProviderParamRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SkipMultipleParams extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething(string $name, string $another)
    {
    }

    public static function provideData(): array
    {
        return ['first', 'second'];
    }
}
