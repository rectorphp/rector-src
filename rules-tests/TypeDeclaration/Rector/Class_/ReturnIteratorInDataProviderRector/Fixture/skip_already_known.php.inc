<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnIteratorInDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipAlreadyKnown extends TestCase
{
    /**
     * @dataProvider someDataProvider
     */
    public function testSomething()
    {
    }

    public function someDataProvider(): \Generator
    {
        yield ['data1'];
        yield ['data2'];
    }
}
