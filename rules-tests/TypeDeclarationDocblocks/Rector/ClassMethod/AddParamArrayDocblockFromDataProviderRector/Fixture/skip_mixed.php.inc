<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SkipMixed extends TestCase
{
    #[DataProvider('provideData')]
    public function test(array $names): void
    {
    }

    public static function provideData($item, $item2)
    {
        yield [[$item, $item2]];
        yield [[$item, $item2]];
    }
}
