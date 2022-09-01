<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class DirectStringConfigTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureDirectStringConfig');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/direct_string_configured_rule.php';
    }
}
