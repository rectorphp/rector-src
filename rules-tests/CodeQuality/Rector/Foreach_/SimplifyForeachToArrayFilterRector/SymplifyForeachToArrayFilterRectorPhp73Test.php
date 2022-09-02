<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SymplifyForeachToArrayFilterRectorPhp73Test extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixturePhp73');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_php73.php';
    }
}
