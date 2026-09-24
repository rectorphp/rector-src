<?php

declare(strict_types=1);

namespace Rector\Tests\Application\ApplicationFileProcessor;

use Rector\Application\ApplicationFileProcessor;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Configuration;

final class ImportNamesCacheTest extends AbstractLazyTestCase
{
    private ApplicationFileProcessor $applicationFileProcessor;

    private ChangedFilesDetector $changedFilesDetector;

    protected function setUp(): void
    {
        parent::setUp();

        self::$rectorConfig = null;
        $this->bootFromConfigFiles([__DIR__ . '/config-import-names.php']);

        $this->applicationFileProcessor = $this->make(ApplicationFileProcessor::class);
        $this->changedFilesDetector = $this->make(ChangedFilesDetector::class);
        $this->changedFilesDetector->clear();
    }

    public function testShortNameNextToSameShortNameAliasIsCachedAsUnchanged(): void
    {
        $filePath = __DIR__ . '/Source/ImportedNextToSameShortNameAlias.php';

        $this->applicationFileProcessor->processFiles([$filePath], new Configuration(isDryRun: true));

        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));
    }
}
