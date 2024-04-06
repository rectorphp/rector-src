<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\Printer;

use Nette\Utils\FileSystem;
use Rector\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class FormatPreservingPrinterTest extends AbstractLazyTestCase
{
    /**
     * @var int
     */
    private const EXPECTED_FILEMOD = 0755;

    private FormatPreservingPrinter $formatPreservingPrinter;

    protected function setUp(): void
    {
        $this->formatPreservingPrinter = $this->make(FormatPreservingPrinter::class);
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

        $filePath = __DIR__ . '/Fixture/file.php';

        $printedFile = $this->formatPreservingPrinter->printToFile($filePath, [], [], []);
        $this->assertStringEqualsFile(__DIR__ . '/Fixture/file.php', $printedFile);

        $this->assertSame(self::EXPECTED_FILEMOD, fileperms(__DIR__ . '/Fixture/file.php') & 0777);
    }
}
