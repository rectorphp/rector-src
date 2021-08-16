<?php

declare(strict_types=1);

namespace Rector\Tests\Php73\Rector\String_\SensitiveHereNowDocRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * Minor differences on windows, see https://github.com/rectorphp/rector/issues/6571
 */
final class SensitiveHereNowDocRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        if ($this->isWindows()) {
            $this->markTestSkipped('minor differences on windows, see https://github.com/rectorphp/rector/issues/6571');
        }

        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
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
