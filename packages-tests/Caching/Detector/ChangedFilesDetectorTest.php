<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Detector;

use Iterator;
use Rector\Caching\Cache;
use Rector\Caching\Config\FileHashComputer;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class ChangedFilesDetectorTest extends AbstractRectorTestCase
{
    private ChangedFilesDetector $changedFilesDetector;

    protected function setUp(): void
    {
        parent::setUp();

        $smartFileSystem = $this->getService(SmartFileSystem::class);

        $cacheDir = sys_get_temp_dir() . '/rector_changed_files_detector_test';
        $fileCacheStorage = new FileCacheStorage(
            $cacheDir,
            $smartFileSystem
        );
        $cache = new Cache($fileCacheStorage);

        // use a local object, instead of this registered service,
        // so this unit test does not clear global ChangedFilesDetector caches
        $this->changedFilesDetector = new ChangedFilesDetector(
            new FileHashComputer(),
            $cache
        );
    }

    protected function tearDown(): void
    {
        $this->changedFilesDetector->clear();
    }

    public function testHasFileChanged(): void
    {
        $smartFileInfo = new SmartFileInfo(__DIR__ . '/Source/file.php');

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($smartFileInfo));
        $this->changedFilesDetector->addFileWithDependencies($smartFileInfo, []);

        $this->assertFalse($this->changedFilesDetector->hasFileChanged($smartFileInfo));
        $this->changedFilesDetector->invalidateFile($smartFileInfo);

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($smartFileInfo));
    }

    /**
     * @param mixed[]|string[] $dependantFiles
     * @dataProvider provideData()
     */
    public function testGetDependentFileInfos(string $filePathName, array $dependantFiles): void
    {
        $smartFileInfo = new SmartFileInfo($filePathName);

        $this->changedFilesDetector->addFileWithDependencies($smartFileInfo, $dependantFiles);
        $dependantSmartFileInfos = $this->changedFilesDetector->getDependentFileInfos($smartFileInfo);

        $dependantFilesCount = count($dependantFiles);

        $this->assertCount($dependantFilesCount, $dependantSmartFileInfos);

        foreach ($dependantFiles as $key => $dependantFile) {
            $this->assertSame($dependantFile, $dependantSmartFileInfos[$key]->getPathname());
        }
    }

    public function provideData(): Iterator
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
