<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\DataProviderArrayItemsNewlinedRector\Fixture;

use PHPUnit\Framework\TestCase;

final class ImageBinaryTest extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function testGetBytesSize(string $content, int $number): void
    {
        // ...
    }

    public function provideData(): array
    {
        return [['content', 8], ['content123', 11]];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\DataProviderArrayItemsNewlinedRector\Fixture;

use PHPUnit\Framework\TestCase;

final class ImageBinaryTest extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function testGetBytesSize(string $content, int $number): void
    {
        // ...
    }

    public function provideData(): array
    {
        return [
            ['content', 8],
            ['content123', 11],
        ];
    }
}

?>
