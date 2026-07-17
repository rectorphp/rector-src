<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use Codeception\Attribute\DataProvider;
use PHPUnit\Framework\TestCase;

final class CodeceptionDataProviderAttribute extends TestCase
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

use Codeception\Attribute\DataProvider;
use PHPUnit\Framework\TestCase;

final class CodeceptionDataProviderAttribute extends TestCase
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
