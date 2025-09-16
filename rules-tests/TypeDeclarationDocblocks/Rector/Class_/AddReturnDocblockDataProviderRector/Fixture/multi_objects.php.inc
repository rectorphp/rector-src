<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MultiObjects extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    public static function provideData(): iterable
    {
        yield [[new DateTime(), 'format']];
        yield [[new DateTimeImmutable(), 'format']];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddReturnDocblockDataProviderRector\Fixture;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MultiObjects extends TestCase
{
    #[DataProvider('provideData')]
    public function testSomething()
    {
    }

    /**
     * @return \Iterator<array<int, array<int, (\DateTime | \DateTimeImmutable | string)>>>
     */
    public static function provideData(): iterable
    {
        yield [[new DateTime(), 'format']];
        yield [[new DateTimeImmutable(), 'format']];
    }
}

?>
