<?php

declare(strict_types=1);

namespace Rector\Core\Console\Command;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\Autoloading\AdditionalAutoloader;
use Rector\Core\Configuration\ConfigInitializer;
use Rector\Core\Configuration\Option;
use Rector\Core\Console\ExitCode;
use Rector\Core\Console\Output\OutputFormatterCollector;
use Rector\Core\Contract\Console\OutputStyleInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Core\Util\MemoryLimiter;
use Rector\Core\Validation\EmptyConfigurableRectorChecker;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Core\ValueObjectFactory\ProcessResultFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ProcessCommand extends AbstractProcessCommand
{
    public function __construct(
        private readonly AdditionalAutoloader $additionalAutoloader,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly ConfigInitializer $configInitializer,
        private readonly ApplicationFileProcessor $applicationFileProcessor,
        private readonly ProcessResultFactory $processResultFactory,
        private readonly DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator,
        private readonly EmptyConfigurableRectorChecker $emptyConfigurableRectorChecker,
        private readonly OutputFormatterCollector $outputFormatterCollector,
        private readonly OutputStyleInterface $rectorOutputStyle,
        private readonly MemoryLimiter $memoryLimiter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('process');
        $this->setDescription('Upgrades or refactors source code with provided rectors');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // missing config? add it :)
        if (! $this->configInitializer->areSomeRectorsLoaded()) {
            $this->configInitializer->createConfig(getcwd());
            return self::SUCCESS;
        }

        $configuration = $this->configurationFactory->createFromInput($input);
        $this->memoryLimiter->adjust($configuration);

        // disable console output in case of json output formatter
        if ($configuration->getOutputFormat() === JsonOutputFormatter::NAME) {
            $this->rectorOutputStyle->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }

        $this->additionalAutoloader->autoloadInput($input);
        $this->additionalAutoloader->autoloadPaths();

        $paths = $configuration->getPaths();

        // 1. add files and directories to static locator
        $this->dynamicSourceLocatorDecorator->addPaths($paths);

        // 2. inform user about registering configurable rule without configuration
        $this->emptyConfigurableRectorChecker->check();

        // MAIN PHASE
        // 3. run Rector
        $systemErrorsAndFileDiffs = $this->applicationFileProcessor->run($configuration, $input);

        // REPORTING PHASE
        // 4. reporting phase
        // report diffs and errors
        $outputFormat = $configuration->getOutputFormat();
        $outputFormatter = $this->outputFormatterCollector->getByName($outputFormat);

        $processResult = $this->processResultFactory->create($systemErrorsAndFileDiffs);
        $outputFormatter->report($processResult, $configuration);

        // invalidate affected files
        $this->invalidateCacheForChangedAndErroredFiles($processResult);

        return $this->resolveReturnCode($processResult, $configuration);
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $application = $this->getApplication();
        if (! $application instanceof Application) {
            throw new ShouldNotHappenException();
        }

        $optionDebug = (bool) $input->getOption(Option::DEBUG);
        if ($optionDebug) {
            $application->setCatchExceptions(false);
        }

        // clear cache
        $optionClearCache = (bool) $input->getOption(Option::CLEAR_CACHE);
        if ($optionDebug || $optionClearCache) {
            $this->changedFilesDetector->clear();
        }
    }

    private function invalidateCacheForChangedAndErroredFiles(ProcessResult $processResult): void
    {
        foreach ($processResult->getChangedFilePaths() as $changedFilePath) {
            $this->changedFilesDetector->invalidateFile($changedFilePath);
        }

        foreach ($processResult->getErrors() as $systemError) {
            $errorFile = $systemError->getFile();
            if (! is_string($errorFile)) {
                continue;
            }

            $this->changedFilesDetector->invalidateFile($errorFile);
        }
    }

    private function resolveReturnCode(ProcessResult $processResult, Configuration $configuration): int
    {
        // some system errors were found → fail
        if ($processResult->getErrors() !== []) {
            return ExitCode::FAILURE;
        }

        // inverse error code for CI dry-run
        if (! $configuration->isDryRun()) {
            return ExitCode::SUCCESS;
        }

        if ($processResult->getFileDiffs() !== []) {
            return ExitCode::CHANGED_CODE;
        }

        return ExitCode::SUCCESS;
    }
}
