<?php

declare(strict_types=1);

namespace Rector\Tests\Caching;

use PHPUnit\Framework\TestCase;
use Rector\Caching\Cache;
use Rector\Caching\DryRunDiffCache;
use Rector\Caching\FileDependencyCollector;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\Util\FileHasher;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\FileProcessResult;
use Rector\ValueObject\Reporting\FileDiff;
use Symfony\Component\Filesystem\Filesystem;

final class DryRunDiffCacheTest extends TestCase
{
    private string $cacheDirectory;

    private string $sourceFilePath;

    private string $dependencyFilePath;

    private FileDependencyCollector $fileDependencyCollector;

    private DryRunDiffCache $dryRunDiffCache;

    protected function setUp(): void
    {
        $this->cacheDirectory = sys_get_temp_dir() . '/rector_dry_run_diff_cache_test_' . getmypid();

        $this->sourceFilePath = $this->cacheDirectory . '/Source.php';
        $this->dependencyFilePath = $this->cacheDirectory . '/Dependency.php';

        $filesystem = new Filesystem();
        $filesystem->mkdir($this->cacheDirectory);
        $filesystem->dumpFile($this->sourceFilePath, "<?php\n\nclass Source\n{\n}\n");
        $filesystem->dumpFile($this->dependencyFilePath, "<?php\n\nclass Dependency\n{\n}\n");

        $fileHasher = new FileHasher();
        $this->fileDependencyCollector = new FileDependencyCollector($fileHasher);

        $this->dryRunDiffCache = new DryRunDiffCache(
            new Cache(new FileCacheStorage($this->cacheDirectory, $filesystem)),
            $fileHasher,
            $this->fileDependencyCollector
        );
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->cacheDirectory);
    }

    public function testSaveLoadRoundTrip(): void
    {
        $file = $this->createFile();
        $configuration = new Configuration(isDryRun: true);

        $this->assertNull($this->dryRunDiffCache->load($file, $configuration));

        $this->fileDependencyCollector->record($this->sourceFilePath, $this->dependencyFilePath);
        $this->dryRunDiffCache->save($file, $configuration, $this->createFileDiff(), true);

        $cachedFileProcessResult = $this->dryRunDiffCache->load($file, $configuration);
        $this->assertInstanceOf(FileProcessResult::class, $cachedFileProcessResult);
        $this->assertTrue($cachedFileProcessResult->hasChanged());

        $loadedFileDiff = $cachedFileProcessResult->getFileDiff();
        $this->assertInstanceOf(FileDiff::class, $loadedFileDiff);
        $this->assertSame('some diff', $loadedFileDiff->getDiff());
        $this->assertSame([RemoveUnusedPrivateMethodRector::class], $loadedFileDiff->getRectorClasses());
    }

    public function testReplayKeepsUnchangedFlag(): void
    {
        $file = $this->createFile();
        $configuration = new Configuration(isDryRun: true);

        // a rule can report line changes while the printed content stays identical
        $this->fileDependencyCollector->record($this->sourceFilePath, $this->dependencyFilePath);
        $this->dryRunDiffCache->save($file, $configuration, $this->createFileDiff(), false);

        $cachedFileProcessResult = $this->dryRunDiffCache->load($file, $configuration);
        $this->assertInstanceOf(FileProcessResult::class, $cachedFileProcessResult);
        $this->assertFalse($cachedFileProcessResult->hasChanged());
    }

    public function testChangedOwnContentInvalidates(): void
    {
        $file = $this->createFile();
        $configuration = new Configuration(isDryRun: true);
        $this->dryRunDiffCache->save($file, $configuration, $this->createFileDiff(), true);

        $changedFile = new File($this->sourceFilePath, "<?php\n\nclass Source\n{\n    public int \$added;\n}\n");
        $this->assertNull($this->dryRunDiffCache->load($changedFile, $configuration));
    }

    public function testChangedDependencyInvalidates(): void
    {
        $file = $this->createFile();
        $configuration = new Configuration(isDryRun: true);

        $this->fileDependencyCollector->record($this->sourceFilePath, $this->dependencyFilePath);
        $this->dryRunDiffCache->save($file, $configuration, $this->createFileDiff(), true);
        $this->assertInstanceOf(FileProcessResult::class, $this->dryRunDiffCache->load($file, $configuration));

        (new Filesystem())->dumpFile(
            $this->dependencyFilePath,
            "<?php\n\nclass Dependency\n{\n    public int \$added;\n}\n"
        );

        // fresh collector = fresh process run, no memoized hashes
        $freshFileHasher = new FileHasher();
        $freshDryRunDiffCache = new DryRunDiffCache(
            new Cache(new FileCacheStorage($this->cacheDirectory, new Filesystem())),
            $freshFileHasher,
            new FileDependencyCollector($freshFileHasher)
        );

        $this->assertNull($freshDryRunDiffCache->load($file, $configuration));
    }

    public function testNoDiffsConfigurationDoesNotCrossReplay(): void
    {
        $file = $this->createFile();

        $this->dryRunDiffCache->save(
            $file,
            new Configuration(isDryRun: true, showDiffs: false),
            $this->createFileDiff(),
            true
        );

        $this->assertNull($this->dryRunDiffCache->load($file, new Configuration(isDryRun: true, showDiffs: true)));
    }

    private function createFile(): File
    {
        return new File($this->sourceFilePath, (string) file_get_contents($this->sourceFilePath));
    }

    private function createFileDiff(): FileDiff
    {
        return new FileDiff($this->sourceFilePath, 'some diff', 'some diff formatted', [
            new RectorWithLineChange(RemoveUnusedPrivateMethodRector::class, 7),
        ]);
    }
}
