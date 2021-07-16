<?php

declare(strict_types=1);

namespace Rector\Tests\DowngradePhp73\Rector\String_\DowngradeFlexibleHeredocSyntaxRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @requires PHP >= 7.3
 */
final class DowngradeFlexibleHeredocSyntaxRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            $this->markTestSkipped('doesnt work on windows, see https://github.com/rectorphp/rector/issues/6571');
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
