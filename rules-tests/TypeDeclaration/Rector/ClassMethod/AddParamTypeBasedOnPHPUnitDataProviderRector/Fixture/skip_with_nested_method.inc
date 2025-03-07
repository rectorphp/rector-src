<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

class SkipWithNestedMethod extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testSomething($number)
    {
    }

    public function dataProvider()
    {
        return [
            $this->generateData()
        ];
    }

    public function generateData(): array
    {
        return [1234];
    }
}
