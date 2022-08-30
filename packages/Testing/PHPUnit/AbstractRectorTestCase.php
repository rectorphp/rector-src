<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use Iterator;
use Nette\Utils\Strings;
use PHPStan\Analyser\NodeScopeResolver;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Container\ContainerInterface;
use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Autoloading\AdditionalAutoloader;
use Rector\Core\Autoloading\BootstrapFilesIncluder;
use Rector\Core\Configuration\ConfigurationFactory;
use Rector\Core\Configuration\Option;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\Testing\Contract\RectorTestInterface;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\Behavior\MovingFilesTrait;
use Symplify\EasyTesting\DataProvider\StaticFixtureUpdater;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractRectorTestCase extends AbstractTestCase implements RectorTestInterface
{
    use MovingFilesTrait;

    protected ParameterProvider $parameterProvider;

    protected RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;

    protected ?SmartFileInfo $originalTempFileInfo = null;

    protected static ?ContainerInterface $allRectorContainer = null;

    private DynamicSourceLocatorProvider $dynamicSourceLocatorProvider;

    private ApplicationFileProcessor $applicationFileProcessor;

    private FilePathHelper $filePathHelper;

    protected function setUp(): void
    {
        // speed up
        @ini_set('memory_limit', '-1');

        $this->includePreloadFilesAndScoperAutoload();

        $configFile = $this->provideConfigFilePath();
        $this->bootFromConfigFiles([$configFile]);

        $this->applicationFileProcessor = $this->getService(ApplicationFileProcessor::class);
        $this->parameterProvider = $this->getService(ParameterProvider::class);
        $this->dynamicSourceLocatorProvider = $this->getService(DynamicSourceLocatorProvider::class);
        $this->filePathHelper = $this->getService(FilePathHelper::class);

        $this->removedAndAddedFilesCollector = $this->getService(RemovedAndAddedFilesCollector::class);
        $this->removedAndAddedFilesCollector->reset();

        /** @var AdditionalAutoloader $additionalAutoloader */
        $additionalAutoloader = $this->getService(AdditionalAutoloader::class);
        $additionalAutoloader->autoloadPaths();

        /** @var BootstrapFilesIncluder $bootstrapFilesIncluder */
        $bootstrapFilesIncluder = $this->getService(BootstrapFilesIncluder::class);
        $bootstrapFilesIncluder->includeBootstrapFiles();
    }

    protected function tearDown(): void
    {
        // free memory and trigger gc to reduce memory peak consumption on windows
        unset(
            $this->applicationFileProcessor,
            $this->parameterProvider,
            $this->dynamicSourceLocatorProvider,
            $this->removedAndAddedFilesCollector,
            $this->originalTempFileInfo,
        );
        gc_collect_cycles();
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    protected function yieldFilesFromDirectory(string $directory, string $suffix = '*.php.inc'): Iterator
    {
        return FixtureFileFinder::yieldDirectory($directory, $suffix);
    }

    protected function isWindows(): bool
    {
        return strncasecmp(PHP_OS, 'WIN', 3) === 0;
    }

    protected function doTestFileInfo(SmartFileInfo $fixtureFileInfo) // , bool $allowMatches = true): void
    {
        if (Strings::match($fixtureFileInfo->getContents(), "#-----\n#")) {
            // changed content
            [$inputFileContents, $expectedFileContents] = FixtureSplitter::loadFileAndSplitInputAndExpected(
                $fixtureFileInfo->getRealPath()
            );
        } else {
            // no change
            $inputFileContents = $fixtureFileInfo->getContents();
            $expectedFileContents = $fixtureFileInfo->getContents();
        }

        $fileSuffix = $this->resolveOriginalFixtureFileSuffix($fixtureFileInfo);

        $inputFileInfo = FixtureTempFileDumper::dump($inputFileContents, $fileSuffix);
        $expectedFileInfo = FixtureTempFileDumper::dump($expectedFileContents, $fileSuffix);

        $this->originalTempFileInfo = $inputFileInfo;

        $this->doTestFileMatchesExpectedContent(
            $inputFileInfo,
            $expectedFileInfo,
            $fixtureFileInfo
        ); //, $allowMatches);
    }

    protected function getFixtureTempDirectory(): string
    {
        return sys_get_temp_dir() . '/_temp_fixture_easy_testing';
    }

    private function resolveOriginalFixtureFileSuffix(\SplFileInfo $splFileInfo): string
    {
        $fixtureRealPath = $splFileInfo->getRealPath();
        if (str_ends_with($fixtureRealPath, '.inc')) {
            $fixtureRealPath = rtrim($fixtureRealPath, '.inc');
        }

        if (str_ends_with($fixtureRealPath, '.blade.php')) {
            return 'blade.php';
        }

        return pathinfo($fixtureRealPath, PATHINFO_EXTENSION);
    }

    private function includePreloadFilesAndScoperAutoload(): void
    {
        if (file_exists(__DIR__ . '/../../../preload.php')) {
            if (file_exists(__DIR__ . '/../../../vendor')) {
                require_once __DIR__ . '/../../../preload.php';
            // test case in rector split package
            } elseif (file_exists(__DIR__ . '/../../../../../../vendor')) {
                require_once __DIR__ . '/../../../preload-split-package.php';
            }
        }

        if (\file_exists(__DIR__ . '/../../../vendor/scoper-autoload.php')) {
            require_once __DIR__ . '/../../../vendor/scoper-autoload.php';
        }
    }

    private function doTestFileMatchesExpectedContent(
        SmartFileInfo $originalFileInfo,
        SmartFileInfo $expectedFileInfo,
        SmartFileInfo $fixtureFileInfo,
        bool $allowMatches = true
    ): void {
        $this->parameterProvider->changeParameter(Option::SOURCE, [$originalFileInfo->getRealPath()]);

        $changedContent = $this->processFileInfo($originalFileInfo);

        // file is removed, we cannot compare it
        if ($this->removedAndAddedFilesCollector->isFileRemoved($originalFileInfo)) {
            return;
        }

        $relativeFilePathFromCwd = $this->filePathHelper->relativePath($fixtureFileInfo->getRealPath());

        try {
            $this->assertStringEqualsFile($expectedFileInfo->getRealPath(), $changedContent);
        } catch (ExpectationFailedException $expectationFailedException) {
            if (! $allowMatches) {
                throw $expectationFailedException;
            }

            StaticFixtureUpdater::updateFixtureContent($originalFileInfo, $changedContent, $fixtureFileInfo);
            $contents = $expectedFileInfo->getContents();

            // make sure we don't get a diff in which every line is different (because of differences in EOL)
            $contents = $this->normalizeNewlines($contents);

            // if not exact match, check the regex version (useful for generated hashes/uuids in the code)
            $this->assertStringMatchesFormat($contents, $changedContent);
        }
    }

    private function normalizeNewlines(string $string): string
    {
        return str_replace("\r\n", "\n", $string);
    }

    private function processFileInfo(SmartFileInfo $fileInfo): string
    {
        $this->dynamicSourceLocatorProvider->setFilePath($fileInfo->getRealPath());

        // needed for PHPStan, because the analyzed file is just created in /temp - need for trait and similar deps
        /** @var NodeScopeResolver $nodeScopeResolver */
        $nodeScopeResolver = $this->getService(NodeScopeResolver::class);
        $nodeScopeResolver->setAnalysedFiles([$fileInfo->getRealPath()]);

        /** @var ConfigurationFactory $configurationFactory */
        $configurationFactory = $this->getService(ConfigurationFactory::class);
        $configuration = $configurationFactory->createForTests([$fileInfo->getRealPath()]);

        $file = new File($fileInfo, $fileInfo->getContents());
        $this->applicationFileProcessor->processFiles([$file], $configuration);

        return $file->getFileContent();
    }
}
