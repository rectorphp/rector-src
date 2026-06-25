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

    public function testOnlyRuleRunCachesUnderOwnScopeWithoutPoisoningFullRun(): void
    {
        $filePath = __DIR__ . '/Source/CleanFile.php';

        $this->applicationFileProcessor->processFiles([$filePath], new Configuration(
            isDryRun: true,
            onlyRule: RemoveEmptyClassMethodRector::class
        ));

        // a repeated --only run hits its own scoped cache entry
        $this->changedFilesDetector->setActiveScope(RemoveEmptyClassMethodRector::class, null);
        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));

        // a full run uses a different scope key, so it is not poisoned
        $this->changedFilesDetector->setActiveScope(null, null);
        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testOnlySuffixRunCachesUnderOwnScopeWithoutPoisoningFullRun(): void
    {
        $filePath = __DIR__ . '/Source/CleanFile.php';

        $this->applicationFileProcessor->processFiles([$filePath], new Configuration(
            isDryRun: true,
            onlySuffix: 'Controller.php'
        ));

        $this->changedFilesDetector->setActiveScope(null, 'Controller.php');
        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));

        $this->changedFilesDetector->setActiveScope(null, null);
        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }
}
