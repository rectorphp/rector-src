<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipTestCaseDataProvider extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function testSomething()
    {
    }

    public function provideData()
    {
        return [
            [
                'name' => 'uri',
            ],
        ];
    }
}
