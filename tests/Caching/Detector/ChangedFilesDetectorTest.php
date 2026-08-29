<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Detector;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ChangedFilesDetectorTest extends AbstractLazyTestCase
{
    private ChangedFilesDetector $changedFilesDetector;

    /**
     * @var mixed[]
     */
    private array $originalRules = [];

    /**
     * @var mixed[]
     */
    private array $originalSkip = [];

    /**
     * @var mixed[]
     */
    private array $originalFileExtensions = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->changedFilesDetector = $this->make(ChangedFilesDetector::class);

        // the parameter bag is a global static shared across the whole test process, restore it after
        $this->originalRules = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES);
        $this->originalSkip = SimpleParameterProvider::provideArrayParameter(Option::SKIP);
        $this->originalFileExtensions = SimpleParameterProvider::provideArrayParameter(Option::FILE_EXTENSIONS);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, $this->originalRules);
        SimpleParameterProvider::setParameter(Option::SKIP, $this->originalSkip);
        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, $this->originalFileExtensions);

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

    public function testCacheKeptWhenRuleRemoved(): void
    {
        $filePath = $this->cacheFileUnderRules(['Rector\\RuleA', 'Rector\\RuleB']);

        // removing a rule means strictly less work, cache stays valid
        $this->applyRules(['Rector\\RuleA']);

        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testCacheClearedWhenRuleAdded(): void
    {
        $filePath = $this->cacheFileUnderRules(['Rector\\RuleA']);

        // adding a rule may refactor files that were clean so far, cache must drop
        $this->applyRules(['Rector\\RuleA', 'Rector\\RuleB']);

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testCacheKeptWhenSkipAdded(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, ['Rector\\RuleA']);
        $filePath = $this->cacheFileUnderRules(['Rector\\RuleA', 'Rector\\RuleB']);

        // adding a skip means strictly less work, cache stays valid
        SimpleParameterProvider::setParameter(Option::SKIP, ['Rector\\RuleA', 'Rector\\RuleB']);
        $this->snapshotConfiguration();

        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testCacheClearedWhenSkipRemoved(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, ['Rector\\RuleA', 'Rector\\RuleB']);
        $filePath = $this->cacheFileUnderRules(['Rector\\RuleA', 'Rector\\RuleB']);

        // removing a skip re-enables transformations, cache must drop
        SimpleParameterProvider::setParameter(Option::SKIP, ['Rector\\RuleA']);
        $this->snapshotConfiguration();

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    public function testCacheClearedWhenOutputAffectingParameterChanged(): void
    {
        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, ['php']);
        $filePath = $this->cacheFileUnderRules(['Rector\\RuleA']);

        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, ['php', 'phtml']);
        $this->snapshotConfiguration();

        $this->assertTrue($this->changedFilesDetector->hasFileChanged($filePath));
    }

    /**
     * @param string[] $rules
     */
    private function cacheFileUnderRules(array $rules): string
    {
        $filePath = __DIR__ . '/Source/file.php';

        $this->applyRules($rules);

        $this->changedFilesDetector->addCacheableFile($filePath);
        $this->changedFilesDetector->cacheFile($filePath);

        // sanity: the file is cached as clean before the configuration change under test
        $this->assertFalse($this->changedFilesDetector->hasFileChanged($filePath));

        return $filePath;
    }

    /**
     * @param string[] $rules
     */
    private function applyRules(array $rules): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, $rules);
        $this->snapshotConfiguration();
    }

    private function snapshotConfiguration(): void
    {
        $this->changedFilesDetector->setFirstResolvedConfigFileInfo(__DIR__ . '/config.php');
    }
}
