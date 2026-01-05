<?php

declare(strict_types=1);

namespace ChangesReporting\Output;

use PHPUnit\Framework\TestCase;
use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\ProcessResult;
use Rector\ValueObject\Reporting\FileDiff;

final class JsonOutputFormatterTest extends TestCase
{
    private readonly JsonOutputFormatter $jsonOutputFormatter;

    protected function setUp(): void
    {
        $this->jsonOutputFormatter = new JsonOutputFormatter();

        parent::setUp();
    }

    public function testGetName(): void
    {
        $this->assertSame('json', $this->jsonOutputFormatter->getName());
    }

    public function testReportShouldShowNumberOfChangesWithNoDiffs(): void
    {
        $this->expectOutputString((string) file_get_contents(__DIR__ . '/Fixtures/without_diffs.json'));

        $this->jsonOutputFormatter->report(
            new ProcessResult(
                [new SystemError('Some error message', 'some/file.php', 1)],
                [
                    new FileDiff(
                        'some/file.php',
                        '--- Original' . PHP_EOL . '+++ New' . PHP_EOL .
                            '@@ -38,5 +39,6 @@' . PHP_EOL .
                            'return true;' . PHP_EOL . '}' . PHP_EOL,
                        'diff console formatted',
                        [new RectorWithLineChange(StrStartsWithRector::class, 38)]
                    ),
                    new FileDiff(
                        'some/file_foo.php',
                        '',
                        '',
                        [new RectorWithLineChange(StrStartsWithRector::class, 38)]
                    ),
                ],
                2
            ),
            new Configuration(showDiffs: false)
        );
    }
}
