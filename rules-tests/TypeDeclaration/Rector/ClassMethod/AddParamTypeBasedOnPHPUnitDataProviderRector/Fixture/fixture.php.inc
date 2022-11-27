<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class SomeTestWithDataProvider extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test($name)
    {
    }

    public function provideData(): Iterator
    {
        yield ['some'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class SomeTestWithDataProvider extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $name)
    {
    }

    public function provideData(): Iterator
    {
        yield ['some'];
    }
}

?>
