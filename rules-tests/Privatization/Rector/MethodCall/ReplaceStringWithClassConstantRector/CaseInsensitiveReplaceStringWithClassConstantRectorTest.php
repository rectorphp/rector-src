<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\Rector\MethodCall\ReplaceStringWithClassConstantRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CaseInsensitiveReplaceStringWithClassConstantRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureCaseInsensitive');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/case_insensitive_configured_config.php';
    }
}
