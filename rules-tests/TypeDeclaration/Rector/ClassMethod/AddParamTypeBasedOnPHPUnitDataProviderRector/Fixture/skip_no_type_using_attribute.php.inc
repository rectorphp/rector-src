<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class SkipNoType extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideData')]
    public function testGetFromId($one)
    {
    }

    public static function provideData(): array
    {
        return [
            [ self::SOMETHING ],
        ];
    }
}
