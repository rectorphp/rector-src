<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector\Fixture;

use PHPUnit\Framework\TestCase;

class SkipDataProvider extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    protected function testSomething()
    {
    }

    public function provideData()
    {

    }
}
