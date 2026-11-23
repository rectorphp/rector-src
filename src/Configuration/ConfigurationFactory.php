<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Agentic\TerminalDetector;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Console\Style\RectorStyle;
use Rector\FileSystem\FilePathFilter;
use Rector\ValueObject\Configuration;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Configuration\ConfigurationFactoryTest
 */
final readonly class ConfigurationFactory
{
    public function __construct(
        private RectorStyle $rectorStyle,
        private OnlyRuleResolver $onlyRuleResolver,
        private FilePathFilter $filePathFilter,
    ) {
    }

    /**
     * @param string[] $paths
     */
    public function createForTests(array $paths): Configuration
    {
        $fileExtensions = SimpleParameterProvider::provideArrayParameter(Option::FILE_EXTENSIONS);

        return new Configuration(
            false,
            true,
            false,
            ConsoleOutputFormatter::NAME,
            $fileExtensions,
            $paths,
            true,
            null,
            null,
            false,
            null,
            false,
            false
        );
    }

    /**
     * Needs to run in the start of the life cycle, since the rest of workflow uses it.
     *
     * @param string[] $commandLinePaths
     * @param string[] $onlyRuleInputs
     */
    public function create(
        array $commandLinePaths,
        bool $isDryRun,
        bool $shouldClearCache,
        bool $noProgressBar,
        bool $noDiffs,
        string $outputFormat,
        array $onlyRuleInputs,
        bool $isComposerBased,
        bool $isPhpOnly,
        ?string $onlySuffix,
        ?string $rawFilter,
        bool $isDebug,
        ?string $memoryLimit,
        string $parallelPort,
        string $parallelIdentifier,
        ?int $maxChanges,
        bool $showRulesSummary,
    ): Configuration {
        $showProgressBar = $this->shouldShowProgressBar($noProgressBar, $outputFormat);

        $showDiffs = $this->shouldShowDiffs($noDiffs);

        $paths = $this->resolvePaths($commandLinePaths);

        $fileExtensions = SimpleParameterProvider::provideArrayParameter(Option::FILE_EXTENSIONS);

        // filter rule and path
        $onlyRules = array_map(
            $this->onlyRuleResolver->resolve(...),
            $onlyRuleInputs
        );

        if ($onlySuffix !== null) {
            $this->rectorStyle->warning(
                'The "--only-suffix" option is deprecated and will be removed. Use "--filter" instead, e.g. --filter="*Controller.php"'
            );
        }

        $filters = $rawFilter !== null ? $this->filePathFilter->parsePatterns($rawFilter) : [];

        // "--only"/"--only-suffix"/"--filter" narrow the run, so skips outside the scope look falsely unused;
        // mark the run as narrowed to disable unused skip reporting and avoid false positives
        if ($onlyRules !== [] || $onlySuffix !== null || $filters !== []) {
            SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, true);
        }

        $isParallel = SimpleParameterProvider::provideBoolParameter(Option::PARALLEL);

        // using debug disables parallel, so emitting exception is straightforward and easier to debug
        if ($isDebug) {
            $isParallel = false;
        }

        $maxChanges = $this->resolveMaxChanges($maxChanges);

        // a global change counter cannot be shared across parallel workers, so enforce the limit in a single process
        if ($maxChanges !== null) {
            $isParallel = false;
        }

        $memoryLimit = $this->resolveMemoryLimit($memoryLimit);

        $isReportingWithRealPath = SimpleParameterProvider::provideBoolParameter(Option::ABSOLUTE_FILE_PATH);

        $levelOverflows = SimpleParameterProvider::provideArrayParameter(Option::LEVEL_OVERFLOWS);

        // "--composer-based" narrows the run the same way "--only" does
        if ($isComposerBased) {
            SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, true);
        }

        // "--php" narrows the run the same way "--only" does
        if ($isPhpOnly) {
            SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, true);
        }

        return new Configuration(
            $isDryRun,
            $showProgressBar,
            $shouldClearCache,
            $outputFormat,
            $fileExtensions,
            $paths,
            $showDiffs,
            $parallelPort,
            $parallelIdentifier,
            $isParallel,
            $memoryLimit,
            $isDebug,
            $isReportingWithRealPath,
            $onlyRules,
            $onlySuffix,
            $levelOverflows,
            $showRulesSummary,
            $isComposerBased,
            $isPhpOnly,
            $filters,
            $maxChanges,
        );
    }

    private function resolveMaxChanges(?int $maxChanges): ?int
    {
        if ($maxChanges === null) {
            return null;
        }

        Assert::positiveInteger($maxChanges);

        return $maxChanges;
    }

    private function shouldShowProgressBar(bool $noProgressBar, string $outputFormat): bool
    {
        if ($noProgressBar) {
            return false;
        }

        // no interactive terminal, e.g. piped output, CI or an agent - the redraws are just noise
        if (! TerminalDetector::isOutputTty()) {
            return false;
        }

        if ($this->rectorStyle->isVerbose()) {
            return false;
        }

        return $outputFormat === ConsoleOutputFormatter::NAME;
    }

    private function shouldShowDiffs(bool $noDiffs): bool
    {
        if ($noDiffs) {
            return false;
        }

        // fallback to parameter
        return ! SimpleParameterProvider::provideBoolParameter(Option::NO_DIFFS, false);
    }

    /**
     * @param string[] $commandLinePaths
     * @return string[]|mixed[]
     */
    private function resolvePaths(array $commandLinePaths): array
    {
        // give priority to command line
        if ($commandLinePaths !== []) {
            // mark the run as narrowed, so unused skip reporting can be disabled to avoid false positives
            SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, true);
            $paths = $commandLinePaths;
        } else {
            // fallback to parameter
            $paths = SimpleParameterProvider::provideArrayParameter(Option::PATHS);
        }

        $this->setFilesWithoutExtensionParameter($paths);

        // extensions read the processed paths from here; without this only the test harness
        // sets it, so the parameter is empty in a real run
        SimpleParameterProvider::setParameter(Option::SOURCE, $paths);

        return $paths;
    }

    /**
     * @param string[] $paths
     */
    private function setFilesWithoutExtensionParameter(array $paths): void
    {
        foreach ($paths as $path) {
            if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === '') {
                $path = realpath($path);

                if ($path === false) {
                    continue;
                }

                SimpleParameterProvider::addParameter(Option::FILES_WITHOUT_EXTENSION, $path);
            }
        }
    }

    private function resolveMemoryLimit(?string $memoryLimit): string|null
    {
        if ($memoryLimit !== null) {
            return $memoryLimit;
        }

        if (! SimpleParameterProvider::hasParameter(Option::MEMORY_LIMIT)) {
            return null;
        }

        return SimpleParameterProvider::provideStringParameter(Option::MEMORY_LIMIT);
    }
}
