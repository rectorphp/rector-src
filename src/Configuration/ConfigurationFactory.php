<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\ValueObject\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Configuration\ConfigurationFactoryTest
 */
final readonly class ConfigurationFactory
{
    public function __construct(
        private SymfonyStyle $symfonyStyle,
        private OnlyRuleResolver $onlyRuleResolver,
    ) {
    }

    /**
     * @api used in tests
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
            false,
            null,
            null
        );
    }

    /**
     * Needs to run in the start of the life cycle, since the rest of workflow uses it.
     */
    public function createFromInput(InputInterface $input): Configuration
    {
        $isDryRun = (bool) $input->getOption(Option::DRY_RUN);
        $shouldClearCache = (bool) $input->getOption(Option::CLEAR_CACHE);

        $outputFormat = (string) $input->getOption(Option::OUTPUT_FORMAT);

        $kaizenStepCount = $input->getOption(Option::KAIZEN);
        if ($kaizenStepCount !== null) {
            $kaizenStepCount = (int) $kaizenStepCount;
            Assert::positiveInteger($kaizenStepCount, 'Change "--kaizen" value to a positive integer');
        }

        $showProgressBar = $this->shouldShowProgressBar($input, $outputFormat);

        $showDiffs = $this->shouldShowDiffs($input);

        $paths = $this->resolvePaths($input);

        $fileExtensions = SimpleParameterProvider::provideArrayParameter(Option::FILE_EXTENSIONS);

        // filter rule and path
        $onlyRule = $input->getOption(Option::ONLY);
        if ($onlyRule !== null) {
            $onlyRule = $this->onlyRuleResolver->resolve($onlyRule);
        }

        $onlySuffix = $input->getOption(Option::ONLY_SUFFIX);

        $isParallel = SimpleParameterProvider::provideBoolParameter(Option::PARALLEL);
        $parallelPort = (string) $input->getOption(Option::PARALLEL_PORT);
        $parallelIdentifier = (string) $input->getOption(Option::PARALLEL_IDENTIFIER);
        $isDebug = (bool) $input->getOption(Option::DEBUG);

        // using debug disables parallel, so emitting exception is straightforward and easier to debug
        if ($isDebug) {
            $isParallel = false;
        }

        $memoryLimit = $this->resolveMemoryLimit($input);

        $isReportingWithRealPath = SimpleParameterProvider::provideBoolParameter(Option::ABSOLUTE_FILE_PATH);

        $levelOverflows = SimpleParameterProvider::provideArrayParameter(Option::LEVEL_OVERFLOWS);

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
            $onlyRule,
            $onlySuffix,
            $levelOverflows,
            $kaizenStepCount
        );
    }

    private function shouldShowProgressBar(InputInterface $input, string $outputFormat): bool
    {
        $noProgressBar = (bool) $input->getOption(Option::NO_PROGRESS_BAR);
        if ($noProgressBar) {
            return false;
        }

        if ($this->symfonyStyle->isVerbose()) {
            return false;
        }

        return $outputFormat === ConsoleOutputFormatter::NAME;
    }

    private function shouldShowDiffs(InputInterface $input): bool
    {
        $noDiffs = (bool) $input->getOption(Option::NO_DIFFS);
        if ($noDiffs) {
            return false;
        }

        // fallback to parameter
        return ! SimpleParameterProvider::provideBoolParameter(Option::NO_DIFFS, false);
    }

    /**
     * @return string[]|mixed[]
     */
    private function resolvePaths(InputInterface $input): array
    {
        $commandLinePaths = (array) $input->getArgument(Option::SOURCE);

        // give priority to command line
        if ($commandLinePaths !== []) {
            $this->setFilesWithoutExtensionParameter($commandLinePaths);
            return $commandLinePaths;
        }

        // fallback to parameter
        $configPaths = SimpleParameterProvider::provideArrayParameter(Option::PATHS);
        $this->setFilesWithoutExtensionParameter($configPaths);

        return $configPaths;
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

    private function resolveMemoryLimit(InputInterface $input): string | null
    {
        $memoryLimit = $input->getOption(Option::MEMORY_LIMIT);
        if ($memoryLimit !== null) {
            return (string) $memoryLimit;
        }

        if (! SimpleParameterProvider::hasParameter(Option::MEMORY_LIMIT)) {
            return null;
        }

        return SimpleParameterProvider::provideStringParameter(Option::MEMORY_LIMIT);
    }
}
