<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Detector;

use FilesystemIterator;
use Nette\Utils\FileSystem;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Webmozart\Assert\Assert;

final class ChangedFilesDetectorPortableCacheTest extends AbstractLazyTestCase
{
    /**
     * @var string[]
     */
    private const array PROJECT_FILE_PATHS = ['src/first.php', 'src/nested/second.php', 'tests/third.php'];

    /**
     * Sits next to the project root rather than inside it, so its cache key starts with `..`.
     */
    private const string OUTSIDE_FILE_PATH = 'shared/outside.php';

    /**
     * Each checkout carries its own copy, so the two configs differ only by absolute path.
     */
    private const string CONFIG_FILE_PATH = 'rector.php';

    private ChangedFilesDetector $changedFilesDetector;

    /**
     * @var mixed[]
     */
    private array $originalPaths = [];

    private string $originalWorkingDirectory;

    private string $rootDirectory;

    private string $firstCheckoutDirectory;

    private string $secondCheckoutDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->changedFilesDetector = $this->make(ChangedFilesDetector::class);

        $workingDirectory = getcwd();
        Assert::string($workingDirectory);
        $this->originalWorkingDirectory = $workingDirectory;

        // canonical, so that paths built here match what getcwd() reports after chdir()
        $temporaryDirectory = realpath(sys_get_temp_dir());
        Assert::string($temporaryDirectory);

        $this->rootDirectory = $temporaryDirectory . '/' . uniqid('rector_portable_cache_');
        $this->firstCheckoutDirectory = $this->rootDirectory . '/first/project';
        $this->secondCheckoutDirectory = $this->rootDirectory . '/second/project';

        $this->createCheckout($this->firstCheckoutDirectory);
        $this->createCheckout($this->secondCheckoutDirectory);

        // the parameter bag is a global static shared across the whole test process
        $this->originalPaths = SimpleParameterProvider::provideArrayParameter(Option::PATHS);

        // the scope is instance state, so pin it rather than inherit whatever ran before
        $this->changedFilesDetector->setActiveScope([], null);

        // start from an empty cache, so entries can be counted rather than compared to a baseline
        $this->changedFilesDetector->clear();
    }

    protected function tearDown(): void
    {
        chdir($this->originalWorkingDirectory);

        FileSystem::delete($this->rootDirectory);

        SimpleParameterProvider::setParameter(Option::PATHS, $this->originalPaths);

        $this->changedFilesDetector->setActiveScope([], null);
        $this->changedFilesDetector->clear();
    }

    public function testCacheBuiltInOneCheckoutIsReusedInAnother(): void
    {
        $this->cacheProjectFilesIn($this->firstCheckoutDirectory);

        chdir($this->secondCheckoutDirectory);

        foreach (self::PROJECT_FILE_PATHS as $projectFilePath) {
            $this->assertFalse(
                $this->changedFilesDetector->hasFileChanged(
                    $this->secondCheckoutDirectory . '/' . $projectFilePath
                ),
                sprintf('"%s" was re-analysed in the second checkout', $projectFilePath)
            );
        }
    }

    public function testSecondCheckoutWritesNoFurtherCacheEntries(): void
    {
        $this->cacheProjectFilesIn($this->firstCheckoutDirectory);
        $entryCountAfterFirstCheckout = $this->countCacheEntries();

        $this->assertSame(count(self::PROJECT_FILE_PATHS), $entryCountAfterFirstCheckout);

        // a full run in the second checkout: every file is offered to the cache again
        $this->cacheProjectFilesIn($this->secondCheckoutDirectory);

        $this->assertSame(
            $entryCountAfterFirstCheckout,
            $this->countCacheEntries(),
            'the second checkout wrote its own set of entries, so it shares no keys with the first'
        );
    }

    public function testCacheCoversFilesOutsideTheProjectRoot(): void
    {
        $outsideFilePath = $this->outsideFilePathFor($this->firstCheckoutDirectory);

        chdir($this->firstCheckoutDirectory);
        $this->changedFilesDetector->addCacheableFile($outsideFilePath);
        $this->changedFilesDetector->cacheFile($outsideFilePath);

        // sanity: the file is cached as clean before the checkout under test changes
        $this->assertFalse($this->changedFilesDetector->hasFileChanged($outsideFilePath));

        chdir($this->secondCheckoutDirectory);

        $this->assertFalse(
            $this->changedFilesDetector->hasFileChanged(
                $this->outsideFilePathFor($this->secondCheckoutDirectory)
            ),
            'a file outside the project root was re-analysed in the second checkout'
        );
    }

    public function testScopedRunReusesItsOwnCacheAcrossCheckouts(): void
    {
        // an --only run keys on the relative path PLUS the scope, so the scope must not
        // smuggle an absolute path back into the key
        $this->changedFilesDetector->setActiveScope(['Rector\\SomeRule'], null);

        $this->cacheProjectFilesIn($this->firstCheckoutDirectory);

        chdir($this->secondCheckoutDirectory);

        foreach (self::PROJECT_FILE_PATHS as $projectFilePath) {
            $this->assertFalse(
                $this->changedFilesDetector->hasFileChanged(
                    $this->secondCheckoutDirectory . '/' . $projectFilePath
                ),
                sprintf('"%s" was re-analysed by a scoped run in the second checkout', $projectFilePath)
            );
        }
    }

    public function testScopedRunReusesFullRunCacheAcrossCheckouts(): void
    {
        // a full run fills the cache in the first checkout
        $this->cacheProjectFilesIn($this->firstCheckoutDirectory);

        // an --only run in the second checkout finds no scoped entry and falls back to the
        // full-run key, which is computed separately and has to be just as portable
        $this->changedFilesDetector->setActiveScope(['Rector\\SomeRule'], null);

        chdir($this->secondCheckoutDirectory);

        foreach (self::PROJECT_FILE_PATHS as $projectFilePath) {
            $this->assertFalse(
                $this->changedFilesDetector->hasFileChanged(
                    $this->secondCheckoutDirectory . '/' . $projectFilePath
                ),
                sprintf('"%s" did not reach the full-run cache from the second checkout', $projectFilePath)
            );
        }
    }

    public function testConfigurationSnapshotSurvivesChangeOfCheckout(): void
    {
        // a full run in the first checkout, recording its configuration alongside the entries
        chdir($this->firstCheckoutDirectory);
        $this->changedFilesDetector->setFirstResolvedConfigFileInfo(
            $this->configFilePathFor($this->firstCheckoutDirectory)
        );
        $this->cacheProjectFilesIn($this->firstCheckoutDirectory);

        // the second checkout holds the same configuration at a different absolute path, which
        // must not read as a changed configuration - that clears every entry, not just one
        chdir($this->secondCheckoutDirectory);
        $this->changedFilesDetector->setFirstResolvedConfigFileInfo(
            $this->configFilePathFor($this->secondCheckoutDirectory)
        );

        foreach (self::PROJECT_FILE_PATHS as $projectFilePath) {
            $this->assertFalse(
                $this->changedFilesDetector->hasFileChanged(
                    $this->secondCheckoutDirectory . '/' . $projectFilePath
                ),
                sprintf('the cache was cleared in the second checkout, so "%s" is gone', $projectFilePath)
            );
        }
    }

    public function testConfiguredPathsDoNotTieTheCacheToOneDirectory(): void
    {
        // `withPaths()` records absolute paths, and they reach the cache-invalidation hash. Two
        // checkouts declare the same paths under different roots, which must hash alike.
        chdir($this->firstCheckoutDirectory);
        SimpleParameterProvider::setParameter(Option::PATHS, [$this->firstCheckoutDirectory . '/src']);
        $firstCheckoutHash = SimpleParameterProvider::hashForCacheInvalidation();

        chdir($this->secondCheckoutDirectory);
        SimpleParameterProvider::setParameter(Option::PATHS, [$this->secondCheckoutDirectory . '/src']);

        $this->assertSame(
            $firstCheckoutHash,
            SimpleParameterProvider::hashForCacheInvalidation(),
            'the configured paths made the same configuration hash differently in another checkout'
        );
    }

    private function createCheckout(string $directory): void
    {
        // identical contents in both checkouts, as two checkouts of one commit are
        foreach (self::PROJECT_FILE_PATHS as $projectFilePath) {
            FileSystem::write($directory . '/' . $projectFilePath, '<?php echo "' . $projectFilePath . '";');
        }

        FileSystem::write($this->outsideFilePathFor($directory), '<?php echo "outside";');
        FileSystem::write($this->configFilePathFor($directory), '<?php return [];');
    }

    private function configFilePathFor(string $checkoutDirectory): string
    {
        return $checkoutDirectory . '/' . self::CONFIG_FILE_PATH;
    }

    private function outsideFilePathFor(string $checkoutDirectory): string
    {
        return dirname($checkoutDirectory) . '/' . self::OUTSIDE_FILE_PATH;
    }

    private function cacheProjectFilesIn(string $directory): void
    {
        chdir($directory);

        foreach (self::PROJECT_FILE_PATHS as $projectFilePath) {
            $filePath = $directory . '/' . $projectFilePath;

            $this->changedFilesDetector->addCacheableFile($filePath);
            $this->changedFilesDetector->cacheFile($filePath);
        }
    }

    private function countCacheEntries(): int
    {
        $cacheDirectory = SimpleParameterProvider::provideStringParameter(Option::CACHE_DIR);
        if (! is_dir($cacheDirectory)) {
            return 0;
        }

        $recursiveDirectoryIterator = new RecursiveDirectoryIterator(
            $cacheDirectory,
            FilesystemIterator::SKIP_DOTS
        );

        $entryCount = 0;
        foreach (new RecursiveIteratorIterator($recursiveDirectoryIterator) as $fileInfo) {
            if ($fileInfo instanceof SplFileInfo && $fileInfo->getExtension() === 'php') {
                ++$entryCount;
            }
        }

        return $entryCount;
    }
}
