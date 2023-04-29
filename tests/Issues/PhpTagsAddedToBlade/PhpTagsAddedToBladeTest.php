<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\PhpTagsAddedToBlade;

use Nette\Utils\FileSystem;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\ValueObject\Configuration;
use Rector\Parallel\ValueObject\Bridge;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symfony\Component\Console\Input\ArrayInput;

final class PhpTagsAddedToBladeTest extends AbstractRectorTestCase
{
    private ApplicationFileProcessor $applicationFileProcessor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->applicationFileProcessor = $this->getService(ApplicationFileProcessor::class);
    }

    public function test(): void
    {
        $inputFilePath = __DIR__ . '/Fixture/php_tags_added_to_blade.input.php';
        $originalInputFileContents = FileSystem::read($inputFilePath);

        $expectedFilePath = __DIR__ . '/Fixture/php_tags_added_to_blade.expected.php';
        $expectedFileContents = FileSystem::read($expectedFilePath);

        $configuration = new Configuration(
            false,
            false,
            true,
            ConsoleOutputFormatter::NAME,
            ['php'],
            [$inputFilePath]
        );

        $systemErrorsAndFileDiffs = $this->applicationFileProcessor->run($configuration, new ArrayInput([]));

        $fileDiffs = $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS];
        $this->assertCount(1, $fileDiffs);

        $changedInputFileContents = FileSystem::read($inputFilePath);
        $this->assertSame($expectedFileContents, $changedInputFileContents);

        // restore original file
        FileSystem::write($inputFilePath, $originalInputFileContents);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/php_tags_added_to_blade.php';
    }
}
