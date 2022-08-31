<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ScopeNotAvailable;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class PregUnmatchedTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureDowngradePregUnmatched');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/callable_this_downgrade_preg_unmatched_configured_rule.php';
    }
}
