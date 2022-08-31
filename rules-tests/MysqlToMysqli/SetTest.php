<?php

declare(strict_types=1);

namespace Rector\Tests\MysqlToMysqli;

use Iterator;
use Rector\Set\ValueObject\SetList;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SetTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return SetList::MYSQL_TO_MYSQLI;
    }
}
