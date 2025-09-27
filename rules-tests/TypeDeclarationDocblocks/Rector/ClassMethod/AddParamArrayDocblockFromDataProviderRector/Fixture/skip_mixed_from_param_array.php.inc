<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SkipMixedFromParamArray extends TestCase
{
    #[DataProvider('provideData')]
    public function test(array $names): void
    {
    }

    public static function provideData()
    {
       return [
            [$item],
            [$item2],
       ];
    }
}
