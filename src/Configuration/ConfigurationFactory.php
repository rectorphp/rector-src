<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\ValueObject\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @see \Rector\Tests\Configuration\ConfigurationFactoryTest
 */
final readonly class ConfigurationFactory
{
    public function __construct(
        private SymfonyStyle $symfonyStyle
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
            0,
            0,
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
        $showProgressBar = $this->shouldShowProgressBar($input, $outputFormat);

        $showDiffs = $this->shouldShowDiffs($input);

        $paths = $this->resolvePaths($input);

        $fileExtensions = SimpleParameterProvider::provideArrayParameter(Option::FILE_EXTENSIONS);

        $isParallel = SimpleParameterProvider::provideBoolParameter(Option::PARALLEL);
        $parallelPort = (string) $input->getOption(Option::PARALLEL_PORT);
        $parallelIdentifier = (string) $input->getOption(Option::PARALLEL_IDENTIFIER);
        $batchIndex = (int) $input->getOption(Option::BATCH_INDEX);
        $batchTotal = (int) $input->getOption(Option::BATCH_TOTAL);
        $isDebug = (bool) $input->getOption(Option::DEBUG);

        // using debug disables parallel and batch running, so emitting exception is straightforward and easier to debug
        if ($isDebug) {
            $isParallel = false;
            $batchTotal = 0;
        }

        $memoryLimit = $this->resolveMemoryLimit($input);

        $isReportingWithRealPath = SimpleParameterProvider::provideBoolParameter(Option::ABSOLUTE_FILE_PATH);

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
            $batchIndex,
            $batchTotal,
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
            return $commandLinePaths;
        }

        // fallback to parameter
        return SimpleParameterProvider::provideArrayParameter(Option::PATHS);
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
