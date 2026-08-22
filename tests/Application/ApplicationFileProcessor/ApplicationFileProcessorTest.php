<?php

declare(strict_types=1);

namespace Rector\Tests\Application\ApplicationFileProcessor;

use Illuminate\Container\Container;
use Rector\Application\ApplicationFileProcessor;
use Rector\Caching\Cache;
use Rector\Caching\CacheFactory;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
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

        // isolate the cache directory to this test - the default directory is shared across all
        // parallel test chunks, and FileCacheStorage::clear() deletes the whole directory, so a
        // sibling chunk can wipe this test's cache between save and load and flip the assertions
        $rectorConfig = self::getContainer();
        SimpleParameterProvider::setParameter(
            Option::CACHE_DIR,
            sys_get_temp_dir() . '/rector_test_application_file_processor'
        );
        $rectorConfig->singleton(
            Cache::class,
            static fn (Container $container): Cache => $container->make(CacheFactory::class)->create()
        );

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
