<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnIteratorInDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipNonDataProvider extends TestCase
{
    public function testSomething()
    {
    }

    public function someDataProvider()
    {
        yield ['data1'];
        yield ['data2'];
    }
}
