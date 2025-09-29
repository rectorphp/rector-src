<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPStan\Type\ObjectType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\Source\AnotherType;

final class KeepStringTypeSimple extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    public static function provideData(): \Iterator
    {
        $anotherTypeObjectType = new ObjectType(AnotherType::class);
        yield [__DIR__ . '/Fixture/new_class.php.inc', 1, $anotherTypeObjectType];
        yield [__DIR__ . '/Fixture/argument_typehint.php.inc', 1, $anotherTypeObjectType];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use PHPStan\Type\ObjectType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\Source\AnotherType;

final class KeepStringTypeSimple extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return \Iterator<array<int, (int | \PHPStan\Type\ObjectType | string)>>
     */
    public static function provideData(): \Iterator
    {
        $anotherTypeObjectType = new ObjectType(AnotherType::class);
        yield [__DIR__ . '/Fixture/new_class.php.inc', 1, $anotherTypeObjectType];
        yield [__DIR__ . '/Fixture/argument_typehint.php.inc', 1, $anotherTypeObjectType];
    }
}

?>
