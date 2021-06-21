<?php

declare(strict_types=1);

namespace Rector\Core\Configuration;

use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\ValueObject\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class ConfigurationFactory
{
    public function __construct(
        private ParameterProvider $parameterProvider
    ) {
    }

    /**
     * Needs to run in the start of the life cycle, since the rest of workflow uses it.
     */
    public function createFromInput(\Symfony\Component\Console\Input\InputInterface $input): Configuration
    {
        $isDryRun = (bool) $input->getOption(Option::DRY_RUN);
        $shouldClearCache = (bool) $input->getOption(Option::CLEAR_CACHE);

        $showProgressBar = $this->canShowProgressBar($input);
        $showDiffs = ! (bool) $input->getOption(Option::NO_DIFFS);

        $outputFormat = (string) $input->getOption(Option::OUTPUT_FORMAT);

        $commandLinePaths = (array) $input->getArgument(Option::SOURCE);

        // manual command line value has priority
        if ($commandLinePaths !== []) {
            $paths = $this->correctBashSpacePaths($commandLinePaths);
        }

        //$isCacheEnabled = (bool) $this->parameterProvider->provideParameter(Option::ENABLE_CACHE);
        $fileExtensions = (array) $this->parameterProvider->provideParameter(Option::FILE_EXTENSIONS);
        $paths = (array) $this->parameterProvider->provideParameter(Option::PATHS);

        return new Configuration(
            isDryRun: $isDryRun,
            shouldClearCache: $shouldClearCache,
            isCacheEnabled: true,
            outputFormat: $outputFormat,
            showProgressBar: $showProgressBar,
            showDiffs: $showDiffs,
            fileExtensions: $fileExtensions,
        );
    }

    private function canShowProgressBar(InputInterface $input): bool
    {
        $noProgressBar = (bool) $input->getOption(Option::NO_PROGRESS_BAR);
        if ($noProgressBar) {
            return false;
        }

        $optionOutputFormat = $input->getOption(Option::OUTPUT_FORMAT);
        return $optionOutputFormat === ConsoleOutputFormatter::NAME;
    }

    /**
     * @param string[] $commandLinePaths
     * @return string[]
     */
    private function correctBashSpacePaths(array $commandLinePaths): array
    {
        // fixes bash edge-case that to merges string with space to one
        foreach ($commandLinePaths as $commandLinePath) {
            if (\str_contains($commandLinePath, ' ')) {
                $commandLinePaths = explode(' ', $commandLinePath);
            }
        }

        return $commandLinePaths;
    }
}
