<?php

declare(strict_types=1);

namespace Rector\Tests\Php73\Rector\String_\SensitiveHereNowDocRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * Minor differences on windows, see https://github.com/rectorphp/rector/issues/6571
 */
final class SensitiveHereNowDocRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        if ($this->isWindows()) {
            $this->markTestSkipped('minor differences on windows, see https://github.com/rectorphp/rector/issues/6571');
        }

        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
