<?php

declare(strict_types=1);

namespace Rector\Core\Console\Command;

use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Configuration\ConfigurationFactory;
use Rector\Core\Configuration\Option;
use Rector\Core\Console\Output\OutputFormatterCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractProcessCommand extends Command
{
    protected ConfigurationFactory $configurationFactory;

    protected OutputFormatterCollector $outputFormatterCollector;

    #[Required]
    public function autowire(
        ConfigurationFactory $configurationFactory,
        OutputFormatterCollector $outputFormatterCollector,
    ): void {
        $this->configurationFactory = $configurationFactory;
        $this->outputFormatterCollector = $outputFormatterCollector;
    }

    protected function configure(): void
    {
        $this->addArgument(
            Option::SOURCE,
            InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
            'Files or directories to be upgraded.'
        );

        $this->addOption(
            Option::DRY_RUN,
            Option::DRY_RUN_SHORT,
            InputOption::VALUE_NONE,
            'Only see the diff of changes, do not save them to files.'
        );

        $this->addOption(
            Option::AUTOLOAD_FILE,
            Option::AUTOLOAD_FILE_SHORT,
            InputOption::VALUE_REQUIRED,
            'Path to file with extra autoload (will be included)'
        );

        $names = $this->outputFormatterCollector->getNames();

        $description = sprintf('Select output format: "%s".', implode('", "', $names));
        $this->addOption(
            Option::OUTPUT_FORMAT,
            Option::OUTPUT_FORMAT_SHORT,
            InputOption::VALUE_OPTIONAL,
            $description,
            ConsoleOutputFormatter::NAME
        );

        $this->addOption(
            Option::NO_PROGRESS_BAR,
            null,
            InputOption::VALUE_NONE,
            'Hide progress bar. Useful e.g. for nicer CI output.'
        );

        $this->addOption(
            Option::NO_DIFFS,
            null,
            InputOption::VALUE_NONE,
            'Hide diffs of changed files. Useful e.g. for nicer CI output.'
        );

        $this->addOption(Option::CLEAR_CACHE, null, InputOption::VALUE_NONE, 'Clear unchaged files cache');
    }
}
