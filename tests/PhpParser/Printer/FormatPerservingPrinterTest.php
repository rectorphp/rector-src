<?php

declare(strict_types=1);

namespace Rector\Core\Tests\PhpParser\Printer;

use Nette\Utils\FileSystem;
use Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FormatPerservingPrinterTest extends AbstractTestCase
{
    /**
     * @var int
     */
    private const EXPECTED_FILEMOD = 0755;

    private FormatPerservingPrinter $formatPerservingPrinter;

    protected function setUp(): void
    {
        $this->boot();
        $this->formatPerservingPrinter = $this->getService(FormatPerservingPrinter::class);
    }

    protected function tearDown(): void
    {
        FileSystem::delete(__DIR__ . '/Fixture');
    }

    public function testFileModeIsPreserved(): void
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            $this->markTestSkipped('file modes are not supported on windows.');
        }

        mkdir(__DIR__ . '/Fixture');
        touch(__DIR__ . '/Fixture/file.php');

        chmod(__DIR__ . '/Fixture/file.php', self::EXPECTED_FILEMOD);

        $fileInfo = new SmartFileInfo(__DIR__ . '/Fixture/file.php');
        $printedFile = $this->formatPerservingPrinter->printToFile($fileInfo, [], [], []);
        $this->assertStringEqualsFile(__DIR__ . '/Fixture/file.php', $printedFile);

        $this->assertSame(self::EXPECTED_FILEMOD, fileperms(__DIR__ . '/Fixture/file.php') & 0777);
    }
}
