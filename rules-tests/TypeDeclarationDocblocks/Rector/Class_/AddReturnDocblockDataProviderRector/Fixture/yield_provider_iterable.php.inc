<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class YieldProviderIterable extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    public static function provideData()
    {
        yield [
            [
                'one' => [1, 2],
                'two' => ['three'],
            ],
        ];
        yield [
            [
                'four' => 'five',
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class YieldProviderIterable extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return \Iterator<array<array<int, array<string, mixed>>, mixed>>
     */
    public static function provideData()
    {
        yield [
            [
                'one' => [1, 2],
                'two' => ['three'],
            ],
        ];
        yield [
            [
                'four' => 'five',
            ],
        ];
    }
}

?>
