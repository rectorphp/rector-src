<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class YieldProvider extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    public static function provideData()
    {
        yield ['data1', 'data2'];
        yield ['item4', 'item5'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class YieldProvider extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return \Iterator<int, string[]>
     */
    public static function provideData()
    {
        yield ['data1', 'data2'];
        yield ['item4', 'item5'];
    }
}

?>
