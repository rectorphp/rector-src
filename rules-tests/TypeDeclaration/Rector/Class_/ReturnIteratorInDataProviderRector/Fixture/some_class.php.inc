<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnIteratorInDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeClass extends TestCase
{
    /**
     * @dataProvider someDataProvider
     */
    public function testSomething()
    {
    }

    public function someDataProvider()
    {
        yield ['data1'];
        yield ['data2'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnIteratorInDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeClass extends TestCase
{
    /**
     * @dataProvider someDataProvider
     */
    public function testSomething()
    {
    }

    public function someDataProvider(): \Iterator
    {
        yield ['data1'];
        yield ['data2'];
    }
}

?>
