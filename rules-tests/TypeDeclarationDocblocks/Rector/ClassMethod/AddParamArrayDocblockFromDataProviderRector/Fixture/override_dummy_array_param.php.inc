<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class OverrideDummyArrayParam extends TestCase
{
    /**
     * @param array $names
     */
    #[DataProvider('provideData')]
    public function test(array $names): void
    {
    }

    public static function provideData()
    {
        yield [['Tom', 'John']];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector\Fixture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class OverrideDummyArrayParam extends TestCase
{
    /**
     * @param string[] $names
     */
    #[DataProvider('provideData')]
    public function test(array $names): void
    {
    }

    public static function provideData()
    {
        yield [['Tom', 'John']];
    }
}

?>
