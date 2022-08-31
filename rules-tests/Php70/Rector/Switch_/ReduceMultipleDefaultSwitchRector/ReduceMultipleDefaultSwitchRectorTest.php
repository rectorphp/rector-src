<?php

declare(strict_types=1);

namespace Rector\Tests\Php70\Rector\Switch_\ReduceMultipleDefaultSwitchRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ReduceMultipleDefaultSwitchRectorTest extends AbstractRectorTestCase
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
        return __DIR__ . '/config/configured_rule.php';
    }
}
