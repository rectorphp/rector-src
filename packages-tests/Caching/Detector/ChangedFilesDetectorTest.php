<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Detector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
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
        parent::tearDown();
        $this->changedFilesDetector->clear();
    }

    public function testHasFileChanged(): void
    {
        $filePath = __DIR__ . '/Source/file.php';

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
        $this->changedFilesDetector->addCachableFile($filePath);
        $this->changedFilesDetector->cacheFileWithDependencies($filePath);

        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));
        $this->changedFilesDetector->invalidateFile($filePath);

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    /**
     * @param mixed[]|string[] $dependantFiles
     */
    #[DataProvider('provideData')]
    public function testGetDependentFileInfos(string $filePath, array $dependantFiles): void
    {
        $this->changedFilesDetector->addFileDependentFiles($filePath, $dependantFiles);
        $this->changedFilesDetector->addCachableFile($filePath);
        $this->changedFilesDetector->cacheFileWithDependencies($filePath);

        $dependantFilePaths = $this->changedFilesDetector->getDependentFilePaths($filePath);

        $dependantFilesCount = count($dependantFiles);

        $this->assertCount($dependantFilesCount, $dependantFilePaths);

        foreach ($dependantFiles as $key => $dependantFile) {
            $this->assertSame($dependantFile, $dependantFilePaths[$key]);
        }
    }

    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Source/file.php', []];

        yield [__DIR__ . '/Source/file.php', [__DIR__ . '/Source/file.php']];

        yield [
            __DIR__ . '/Source/file.php',
            [__DIR__ . '/Source/file.php', __DIR__ . '/Source/file2.php', __DIR__ . '/Source/file3.php'],
        ];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config.php';
    }
}
