<?php

declare(strict_types=1);

namespace Rector\Tests\Application\ApplicationFileProcessor;

use Rector\Application\ApplicationFileProcessor;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Configuration;

final class ApplicationFileProcessorTest extends AbstractLazyTestCase
{
    private ApplicationFileProcessor $applicationFileProcessor;

    private ChangedFilesDetector $changedFilesDetector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->applicationFileProcessor = $this->make(ApplicationFileProcessor::class);
        $this->changedFilesDetector = $this->make(ChangedFilesDetector::class);
    }

    protected function tearDown(): void
    {
        $this->changedFilesDetector->clear();
    }

    public function testCleanFileIsCachedAsUnchanged(): void
    {
        $filePath = __DIR__ . '/Source/CleanFile.php';

        $this->applicationFileProcessor->processFiles([$filePath], new Configuration(isDryRun: true));

        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testOnlyRuleRunDoesNotCacheFileAsUnchanged(): void
    {
        $filePath = __DIR__ . '/Source/CleanFile.php';

        $this->applicationFileProcessor->processFiles([$filePath], new Configuration(
            isDryRun: true,
            onlyRule: RemoveEmptyClassMethodRector::class
        ));

        // a file clean under one rule is not necessarily clean under all rules
        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testOnlySuffixRunDoesNotCacheFileAsUnchanged(): void
    {
        $filePath = __DIR__ . '/Source/CleanFile.php';

        $this->applicationFileProcessor->processFiles([$filePath], new Configuration(
            isDryRun: true,
            onlySuffix: 'Controller.php'
        ));

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }
}
