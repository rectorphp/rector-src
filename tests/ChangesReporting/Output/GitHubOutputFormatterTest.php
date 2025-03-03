<?php

declare(strict_types=1);

namespace Rector\Tests\ChangesReporting\Output;

use PHPUnit\Framework\TestCase;
use Rector\ChangesReporting\Output\GitHubOutputFormatter;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\ProcessResult;
use Rector\ValueObject\Reporting\FileDiff;

final class GitHubOutputFormatterTest extends TestCase
{
    private readonly GitHubOutputFormatter $gitHubOutputFormatter;

    protected function setUp(): void
    {
        $this->gitHubOutputFormatter = new GitHubOutputFormatter();

        parent::setUp();
    }

    public function testGetName(): void
    {
        $this->assertSame('github', $this->gitHubOutputFormatter->getName());
    }

    public function testReportShouldOutputErrorMessagesGrouped(): void
    {

        $this->expectOsOutputString(
            '::group::Rector report' . PHP_EOL .
                '::error file=some/file.php,line=1::Some error message' . PHP_EOL .
                '::error file=some/file.php,line=38::StrStartsWithRector%0A%0A--- Original%0A+++ New%0A@@ -38,5 +39,6 @@%0Areturn true;%0A}%0A' . PHP_EOL .
                '::endgroup::' . PHP_EOL
        );

        $this->gitHubOutputFormatter->report(
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
                ],
            ),
            new Configuration()
        );
    }

    public function testReportShouldOutputErrorMessagesGroupedWithNoErrors(): void
    {
        $this->expectOsOutputString('::group::Rector report' . PHP_EOL . '::endgroup::' . PHP_EOL);

        $this->gitHubOutputFormatter->report(new ProcessResult([], []), new Configuration());
    }

    public function testReportShouldOutputErrorMessagesGroupedWithMultipleDiffs(): void
    {
        $this->expectOsOutputString(
            '::group::Rector report' . PHP_EOL .
                '::error file=some/file.php,line=38::StrStartsWithRector / NullToStrictStringFuncCallArgRector%0A%0A--- Original%0A+++ New%0A@@ -38,5 +39,6 @@%0Areturn true;%0A}%0A' . PHP_EOL .
                '::error file=some/another-file.php,line=54::StrStartsWithRector%0A%0A--- Original%0A+++ New%0A@@ -54,10 +54,10 @@%0Areturn true;%0A}%0A' . PHP_EOL .
                '::endgroup::' . PHP_EOL
        );

        $this->gitHubOutputFormatter->report(
            new ProcessResult([], [
                new FileDiff(
                    'some/file.php',
                    '--- Original' . PHP_EOL . '+++ New' . PHP_EOL .
                        '@@ -38,5 +39,6 @@' . PHP_EOL .
                        'return true;' . PHP_EOL . '}' . PHP_EOL,
                    'diff console formatted',
                    [
                        new RectorWithLineChange(StrStartsWithRector::class, 38),
                        new RectorWithLineChange(NullToStrictStringFuncCallArgRector::class, 38),
                    ]
                ),
                new FileDiff(
                    'some/another-file.php',
                    '--- Original' . PHP_EOL . '+++ New' . PHP_EOL .
                        '@@ -54,10 +54,10 @@' . PHP_EOL .
                        'return true;' . PHP_EOL . '}' . PHP_EOL,
                    'diff console formatted',
                    [new RectorWithLineChange(StrStartsWithRector::class, 54)]
                ),
            ], ),
            new Configuration()
        );
    }

    protected function expectOsOutputString(string $expectedOutput): void
    {
        $isWindows = strncasecmp(PHP_OS, 'WIN', 3) === 0;
        if ($isWindows) {
            $expectedOutput = str_replace('%0A', '%0D%0A', $expectedOutput);
        }

        parent::expectOutputString($expectedOutput);
    }
}
