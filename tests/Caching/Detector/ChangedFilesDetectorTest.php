<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Detector;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Caching\FileDependencyCollector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ChangedFilesDetectorTest extends AbstractLazyTestCase
{
    private ChangedFilesDetector $changedFilesDetector;

    private FileDependencyCollector $fileDependencyCollector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->changedFilesDetector = $this->make(ChangedFilesDetector::class);
        $this->fileDependencyCollector = $this->make(FileDependencyCollector::class);
        $this->fileDependencyCollector->reset();
    }

    protected function tearDown(): void
    {
        $this->changedFilesDetector->clear();
    }

    public function testHasFileChanged(): void
    {
        $filePath = __DIR__ . '/Source/file.php';

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
        $this->changedFilesDetector->addCacheableFile($filePath);
        $this->changedFilesDetector->cacheFile($filePath);

        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));
        $this->changedFilesDetector->invalidateFile($filePath);

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testDependencyChangeInvalidatesFile(): void
    {
        $filePath = __DIR__ . '/Source/file.php';
        $dependencyFilePath = sys_get_temp_dir() . '/rector_changed_files_detector_dependency_test.php';
        file_put_contents($dependencyFilePath, "<?php\n\nclass DetectorDependencyFixture\n{\n}\n");

        $this->fileDependencyCollector->record($filePath, $dependencyFilePath);
        $this->changedFilesDetector->addCacheableFile($filePath);
        $this->changedFilesDetector->cacheFile($filePath);

        // simulate a fresh process run with unchanged files
        $this->fileDependencyCollector->reset();
        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));

        // a dependency change must invalidate the file, even though its own content is unchanged
        file_put_contents(
            $dependencyFilePath,
            "<?php\n\nclass DetectorDependencyFixture\n{\n    public function added(): int\n    {\n        return 1;\n    }\n}\n"
        );
        $this->fileDependencyCollector->reset();
        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));

        unlink($dependencyFilePath);
    }

    public function testFailedDependencyCapturePreventsCaching(): void
    {
        $filePath = __DIR__ . '/Source/file.php';

        // a failed capture means the dependency set may be incomplete → never cache
        $this->fileDependencyCollector->markFailed($filePath);
        $this->changedFilesDetector->addCacheableFile($filePath);
        $this->changedFilesDetector->cacheFile($filePath);

        $this->fileDependencyCollector->reset();
        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config.php';
    }
}
