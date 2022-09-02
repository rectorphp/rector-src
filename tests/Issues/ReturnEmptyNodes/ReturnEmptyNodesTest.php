<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ReturnEmptyNodes;

use Iterator;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ReturnEmptyNodesTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->expectExceptionMessage('Array of nodes cannot be empty');
        $this->expectException(ShouldNotHappenException::class);

        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
