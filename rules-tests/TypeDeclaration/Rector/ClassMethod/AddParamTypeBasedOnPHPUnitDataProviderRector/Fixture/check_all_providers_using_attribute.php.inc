<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class MultipleProviders extends TestCase
{
    public function dataProvider1(): iterable {
        yield [1];
        yield [null];
    }

    public function dataProvider2(): iterable {
        yield ['foo'];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dataProvider1')]
    #[\PHPUnit\Framework\Attributes\DataProvider('dataProvider2')]
    public function testGetFromId($data): void {
    }
}

?>
