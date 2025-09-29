<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UnionSameArrayType extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    public static function provideData(): \Iterator
    {
        yield ['...', __DIR__ . '/Source/expected/expected.txt'];
        yield ["-old\n+new", __DIR__ . '/Source/expected/expected_old_new.txt'];

        yield [
            FileSystem::read('...'),
            __DIR__ . '/Fixture/expected_with_full_diff_by_phpunit.diff',
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UnionSameArrayType extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return \Iterator<array<int, string>>
     */
    public static function provideData(): \Iterator
    {
        yield ['...', __DIR__ . '/Source/expected/expected.txt'];
        yield ["-old\n+new", __DIR__ . '/Source/expected/expected_old_new.txt'];

        yield [
            FileSystem::read('...'),
            __DIR__ . '/Fixture/expected_with_full_diff_by_phpunit.diff',
        ];
    }
}

?>
