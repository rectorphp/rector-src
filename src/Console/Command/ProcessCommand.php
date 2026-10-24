<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Entropy\Console\Contract\CommandInterface;
use Entropy\Console\Contract\DefaultCommandInterface;
use Rector\Application\ApplicationFileProcessor;
use Rector\Autoloading\AdditionalAutoloader;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\Configuration\ConfigInitializer;
use Rector\Configuration\ConfigurationFactory;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Console\ExitCode;
use Rector\Console\Output\OutputFormatterCollector;
use Rector\Console\Style\RectorStyle;
use Rector\Reporting\DeprecatedRulesReporter;
use Rector\Reporting\MissConfigurationReporter;
use Rector\Skipper\Skipper\Skipper;
use Rector\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Util\MemoryLimiter;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Configuration\LevelOverflow;
use Rector\ValueObject\ProcessResult;

final readonly class ProcessCommand implements CommandInterface, DefaultCommandInterface
{
    public function __construct(
        private AdditionalAutoloader $additionalAutoloader,
        private ChangedFilesDetector $changedFilesDetector,
        private ConfigInitializer $configInitializer,
        private ApplicationFileProcessor $applicationFileProcessor,
        private DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator,
        private OutputFormatterCollector $outputFormatterCollector,
        private RectorStyle $rectorStyle,
        private MemoryLimiter $memoryLimiter,
        private ConfigurationFactory $configurationFactory,
        private DeprecatedRulesReporter $deprecatedRulesReporter,
        private MissConfigurationReporter $missConfigurationReporter,
        private ConfigurationRuleFilter $configurationRuleFilter,
        private Skipper $skipper,
    ) {
    }

    public function getName(): string
    {
        return 'process';
    }

    public function getDescription(): string
    {
        return 'Upgrades or refactors source code with provided Rector rules';
    }

    /**
     * @param string[] $only           Fully qualified rule class name; repeat to run several rules
     * @param string   $config         Path to config file
     * @param string   $autoloadFile   Path to file with extra autoload (will be included)
     * @param string   $outputFormat   Select output format
     * @param string   $onlySuffix     Deprecated, use "--filter" instead
     * @param string   $filter         Keep only files matching all comma-separated patterns
     * @param string   $memoryLimit    Memory limit for process
     * @param string   $maxChanges     Stop after this many changes are made
     * @param string   $port           [INTERNAL] parallel TCP port
     * @param string   $identifier     [INTERNAL] parallel identifier
     * @param string ...$paths         Files or directories to be upgraded
     *
     * @option $only
     * @option $config
     * @option $autoloadFile
     * @option $outputFormat
     * @option $onlySuffix
     * @option $filter
     * @option $memoryLimit
     * @option $maxChanges
     * @option $port
     * @option $identifier
     *
     * @api invoked via reflection by the Entropy console application
     *
     * @return ExitCode::*
     */
    public function run(
        bool $dryRun = false,
        bool $clearCache = false,
        bool $noProgressBar = false,
        bool $noDiffs = false,
        bool $debug = false,
        bool $composerBased = false,
        bool $php = false,
        bool $rulesSummary = false,
        array $only = [],
        string $config = '',
        string $autoloadFile = '',
        string $outputFormat = ConsoleOutputFormatter::NAME,
        string $onlySuffix = '',
        string $filter = '',
        string $memoryLimit = '',
        string $maxChanges = '',
        string $port = '',
        string $identifier = '',
        string ...$paths,
    ): int {
        // missing config? add it :)
        if (! $this->configInitializer->areSomeRectorsLoaded()) {
            $this->configInitializer->createConfig((string) getcwd());
            return ExitCode::SUCCESS;
        }

        $configuration = $this->configurationFactory->create(
            array_values($paths),
            $dryRun,
            $clearCache,
            $noProgressBar,
            $noDiffs,
            $outputFormat,
            $only,
            $composerBased,
            $php,
            $onlySuffix !== '' ? $onlySuffix : null,
            $filter !== '' ? $filter : null,
            $debug,
            $memoryLimit !== '' ? $memoryLimit : null,
            $port,
            $identifier,
            $maxChanges !== '' ? (int) $maxChanges : null,
            $rulesSummary,
        );

        // clear cache in debug run, so nothing is skipped and errors show up clearly
        if ($debug) {
            $this->changedFilesDetector->clear();
        }

        $this->memoryLimiter->adjust($configuration);
        $this->configurationRuleFilter->setConfiguration($configuration);

        // disable console output in case of json output formatter
        if ($configuration->getOutputFormat() === JsonOutputFormatter::NAME) {
            $this->rectorStyle->setVerbosity(RectorStyle::VERBOSITY_QUIET);
        }

        // "--rules-summary" is a human-only table; the JSON format carries richer per-rule data
        if ($configuration->shouldShowRulesSummary()) {
            $this->rectorStyle->getErrorStyle()
                ->warning(
                    'The "--rules-summary" option is deprecated and will be removed. Use "--output-format=json" instead, which reports every applied rule with its file and line.'
                );
        }

        $this->additionalAutoloader->autoloadFile();

        $paths = $configuration->getPaths();

        // 0. warn about too high levels
        foreach ($configuration->getLevelOverflows() as $levelOverflow) {
            $this->reportLevelOverflow($levelOverflow);
        }

        // 0. warn about skipped rules that are deprecated
        if ($this->skipper->resolveDeprecatedSkippedClasses() !== []) {
            $this->rectorStyle->warning(sprintf(
                'These rules are skipped, but are deprecated. Most likely you do not need to skip them anymore as not part of any set and remove them: %s%s',
                "\n\n",
                '* ' . implode("\n* ", $this->skipper->resolveDeprecatedSkippedClasses()) . "\n"
            ));
        }

        // 1. warn about rules registered in both withRules() and sets to avoid bloated rector.php configs
        $setAndRulesDuplicatedRegistrations = $configuration->getBothSetAndRulesDuplicatedRegistrations();
        if ($setAndRulesDuplicatedRegistrations !== []) {
            $this->rectorStyle->warning(sprintf(
                'These rules are registered in both sets and "withRules()". Remove them from "withRules()" to avoid duplications: %s* %s',
                "\n\n",
                implode(' * ', $setAndRulesDuplicatedRegistrations) . "\n"
            ));
        }

        // 2. add files and directories to static locator
        $this->dynamicSourceLocatorDecorator->addPaths($paths);
        if ($this->dynamicSourceLocatorDecorator->arePathsEmpty()) {
            // read from rector.php, no paths definition needs withPaths() config
            if ($paths === []) {
                $this->rectorStyle->error(
                    'Provide paths in Rector config. See ways: https://getrector.com/documentation/define-paths'
                );

                return ExitCode::FAILURE;
            }

            // read from cli paths arguments, eg: vendor/bin/rector process A B C which A, B, and C not exists
            $isSingular = count($paths) === 1;
            $this->rectorStyle->error(
                sprintf(
                    'The following given path%s do%s not match any file%s or director%s: %s%s',
                    $isSingular ? '' : 's',
                    $isSingular ? 'es' : '',
                    $isSingular ? '' : 's',
                    $isSingular ? 'y' : 'ies',
                    "\n\n" . ' - ',
                    implode("\n" . ' - ', $paths)
                )
            );

            return ExitCode::FAILURE;
        }

        // autoload paths is register to DynamicSourceLocatorProvider,
        // so check after arePathsEmpty() above
        // check in no parallel since parallel will require register on its own process
        if (! $configuration->isParallel()) {
            $this->additionalAutoloader->autoloadPaths();
        }

        // MAIN PHASE
        // 2. run Rector
        $processResult = $this->applicationFileProcessor->run($configuration);

        // REPORTING PHASE
        // 3. reporting phaseRunning 2nd time with collectors data
        // report diffs and errors
        $outputFormat = $configuration->getOutputFormat();

        // warn about deprecated static-analysis-oriented output formats, to be removed in next minor version
        if (in_array($outputFormat, ['github', 'gitlab'], true)) {
            $this->rectorStyle->getErrorStyle()
                ->warning(sprintf(
                    'The "%s" output format is deprecated and will be removed in the next minor version, as Rector is not a static analysis tool.',
                    $outputFormat
                ));
        }

        $outputFormatter = $this->outputFormatterCollector->getByName($outputFormat);

        $outputFormatter->report($processResult, $configuration);

        // 4. Deprecations reporter
        $this->deprecatedRulesReporter->reportDeprecatedRules();
        $this->deprecatedRulesReporter->reportDeprecatedSkippedRules();
        $this->deprecatedRulesReporter->reportDeprecatedAttributesSetsArgs();
        $this->deprecatedRulesReporter->reportDeprecatedComposerBasedArgs();

        $this->missConfigurationReporter->reportSkippedNeverRegisteredRules();
        $this->missConfigurationReporter->reportSkippedNonRectorClasses();
        $this->missConfigurationReporter->reportUnusedSkips($processResult);

        return $this->resolveReturnCode($processResult, $configuration);
    }

    /**
     * @return ExitCode::*
     */
    private function resolveReturnCode(ProcessResult $processResult, Configuration $configuration): int
    {
        // some system errors were found → fail
        if ($processResult->getSystemErrors() !== []) {
            return ExitCode::FAILURE;
        }

        // inverse error code for CI dry-run
        if (! $configuration->isDryRun()) {
            return ExitCode::SUCCESS;
        }

        if ($processResult->getFileDiffs() !== []) {
            return ExitCode::CHANGED_CODE;
        }

        if ($processResult->getTotalChanged() > 0) {
            return ExitCode::CHANGED_CODE;
        }

        return ExitCode::SUCCESS;
    }

    private function reportLevelOverflow(LevelOverflow $levelOverflow): void
    {
        $suggestedSetMethod = PHP_VERSION_ID >= 80000 ? sprintf(
            '->withPreparedSets(%s: true)',
            $levelOverflow->getSuggestedRuleset()
        ) : sprintf('->withSets(SetList::%s)', $levelOverflow->getSuggestedSetListConstant());

        $this->rectorStyle->warning(sprintf(
            'The "->%s()" level contains only %d rules, but you set level to %d.%sYou are using the full set now! Time to switch to more efficient "%s".',
            $levelOverflow->getConfigurationName(),
            $levelOverflow->getRuleCount(),
            $levelOverflow->getLevel(),
            "\n",
            $suggestedSetMethod,
        ));
    }
}
