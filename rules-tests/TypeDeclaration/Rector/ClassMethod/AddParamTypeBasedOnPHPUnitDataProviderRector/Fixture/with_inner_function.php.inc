<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class WithInnerFunction extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test($name)
    {
    }

    public function provideData()
    {
        function () {
            return [['some']];
        };

        yield [1];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class WithInnerFunction extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(int $name)
    {
    }

    public function provideData()
    {
        function () {
            return [['some']];
        };

        yield [1];
    }
}

?>
