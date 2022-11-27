<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class ReturnArray extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test($name, $number)
    {
    }

    public function provideData()
    {
        return [['some', 100]];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class ReturnArray extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $name, int $number)
    {
    }

    public function provideData()
    {
        return [['some', 100]];
    }
}

?>
