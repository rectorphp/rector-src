<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CovertIterable extends TestCase
{
    #[DataProvider('provideData')]
    public function test(iterable $names): void
    {
    }

    public static function provideData()
    {
        yield [['Tom', 'John']];
        yield [[]];
        yield [['John', 'Doe']];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CovertIterable extends TestCase
{
    /**
     * @param string[] $names
     */
    #[DataProvider('provideData')]
    public function test(iterable $names): void
    {
    }

    public static function provideData()
    {
        yield [['Tom', 'John']];
        yield [[]];
        yield [['John', 'Doe']];
    }
}

?>
