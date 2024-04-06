<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Detector;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ChangedFilesDetectorTest extends AbstractLazyTestCase
{
    private ChangedFilesDetector $changedFilesDetector;

    protected function setUp(): void
    {
        $this->changedFilesDetector = $this->make(ChangedFilesDetector::class);
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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config.php';
    }
}
