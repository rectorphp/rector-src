<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ChangeSwitchToMatchRectorSkippedTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureSkipped');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_php_version_74.php';
    }
}
