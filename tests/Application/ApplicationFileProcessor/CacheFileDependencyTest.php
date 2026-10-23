<?php

declare(strict_types=1);

namespace Rector\Tests\Application\ApplicationFileProcessor;

use Nette\Utils\FileSystem;
use Rector\Application\ApplicationFileProcessor;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Configuration;

/**
 * Reproduces the cross-file cache false negative of https://github.com/rectorphp/rector-src/issues/8493
 *
 * A child's output depends on its parent, but the cache keys the child only by its own contents.
 * When the parent changes and the child does not, the child's entry still looks valid, so the child
 * is skipped and the transformation it now needs is silently missed.
 */
final class CacheFileDependencyTest extends AbstractLazyTestCase
{
    private ApplicationFileProcessor $applicationFileProcessor;

    private ChangedFilesDetector $changedFilesDetector;

    private string $temporaryDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        self::$rectorConfig = null;
        $this->bootFromConfigFiles([__DIR__ . '/config-parent-return-type.php']);

        $this->applicationFileProcessor = $this->make(ApplicationFileProcessor::class);
        $this->changedFilesDetector = $this->make(ChangedFilesDetector::class);

        $this->temporaryDirectory = sys_get_temp_dir() . '/_rector_cache_file_dependency_test_src';
    }

    protected function tearDown(): void
    {
        $this->changedFilesDetector->clear();
        FileSystem::delete($this->temporaryDirectory);
    }

    public function testChildIsNotReconsideredWhenParentChanges(): void
    {
        $parentFilePath = $this->temporaryDirectory . '/ParentClass.php';
        $childFilePath = $this->temporaryDirectory . '/ChildClass.php';

        // parent method has no return type yet, so the child gets nothing to copy
        FileSystem::write($parentFilePath, $this->createParentContents(''));
        FileSystem::write($childFilePath, $this->createChildContents());

        $this->applicationFileProcessor->processFiles(
            [$parentFilePath, $childFilePath],
            new Configuration(isDryRun: true)
        );

        // both files are clean on this run and cached as such
        $this->assertFalse($this->changedFilesDetector->hasFileChanged($childFilePath));

        // the parent now declares a return type the child should inherit on the next run
        FileSystem::write($parentFilePath, $this->createParentContents(': string'));

        // BUG #8493: the child's output now depends on the changed parent, yet the cache keys the
        // child only by its own (untouched) contents, so it is wrongly reported as unchanged.
        // Once per-file dependency tracking lands, flip this to assertTrue.
        $this->assertFalse($this->changedFilesDetector->hasFileChanged($childFilePath));
    }

    private function createParentContents(string $returnType): string
    {
        return <<<PHP
            <?php

            namespace RectorCacheDependency;

            class ParentClass
            {
                public function run(){$returnType}
                {
                }
            }
            PHP;
    }

    private function createChildContents(): string
    {
        return <<<'PHP'
            <?php

            namespace RectorCacheDependency;

            class ChildClass extends ParentClass
            {
                public function run()
                {
                }
            }
            PHP;
    }
}
