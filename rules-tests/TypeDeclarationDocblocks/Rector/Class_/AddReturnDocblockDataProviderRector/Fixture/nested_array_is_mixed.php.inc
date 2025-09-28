<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NestedArrayIsMixed extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    public static function provideData(): \Generator
    {
        yield [[]];
        yield [[1, 2, 3, 4]];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NestedArrayIsMixed extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return \Generator<array<int, array<mixed>>>
     */
    public static function provideData(): \Generator
    {
        yield [[]];
        yield [[1, 2, 3, 4]];
    }
}

?>
