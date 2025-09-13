<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DataProviderMethod extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    public static function provideData()
    {
        return [
            ['data1'],
            ['data2'],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DataProviderMethod extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return string[][]
     */
    public static function provideData()
    {
        return [
            ['data1'],
            ['data2'],
        ];
    }
}

?>
