<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipExisting extends TestCase
{
    public function provideThings(): array
    {
        return [
            [ 123 ],
            [ 'I am a string' ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideThings')]
    public function testGetFromId(int $one)
    {

    }
}
