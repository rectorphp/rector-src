<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Entropy\Console\Contract\CommandInterface;
use Nette\Utils\Json;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\Console\ExitCode;
use Rector\Console\Style\RectorStyle;
use Rector\Reporting\DeprecatedRulesReporter;
use Rector\Reporting\MissConfigurationReporter;
use Rector\Skipper\Skipper\Skipper;
use Rector\ValueObject\Configuration;

/**
 * @see \Rector\Tests\Console\Command\ValidateConfigCommandTest
 */
final readonly class ValidateConfigCommand implements CommandInterface
{
    public function __construct(
        private RectorStyle $rectorStyle,
        private DeprecatedRulesReporter $deprecatedRulesReporter,
        private MissConfigurationReporter $missConfigurationReporter,
        private Skipper $skipper,
    ) {
    }

    public function getName(): string
    {
        return 'validate-config';
    }

    public function getDescription(): string
    {
        return 'Report config hygiene issues without processing any files';
    }

    /**
     * @param string $outputFormat Output format: "console" or "json"
     *
     * @option $outputFormat
     *
     * @api invoked via reflection by the Entropy console application
     *
     * @return ExitCode::*
     */
    public function run(string $outputFormat = ConsoleOutputFormatter::NAME): int
    {
        $isJsonOutput = $outputFormat === JsonOutputFormatter::NAME;

        // silence the human-readable warnings, so only the JSON payload lands on stdout
        if ($isJsonOutput) {
            $this->rectorStyle->setVerbosity(RectorStyle::VERBOSITY_QUIET);
        }

        $issueCount = 0;

        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedRules();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedSkippedRules();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedAttributesSetsArgs();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedComposerBasedArgs();

        $issueCount += $this->missConfigurationReporter->reportSkippedNeverRegisteredRules();
        $issueCount += $this->missConfigurationReporter->reportSkippedNonRectorClasses();

        $issueCount += $this->reportDeprecatedSkippedClasses();
        $issueCount += $this->reportSetAndRulesDuplicatedRegistrations();

        if ($isJsonOutput) {
            echo Json::encode([
                'valid' => $issueCount === 0,
                'issue_count' => $issueCount,
            ], pretty: true) . PHP_EOL;
            return $issueCount === 0 ? ExitCode::SUCCESS : ExitCode::FAILURE;
        }

        if ($issueCount === 0) {
            $this->rectorStyle->success('Config is valid, no issues found');
            return ExitCode::SUCCESS;
        }

        $this->rectorStyle->error(sprintf(
            '%d config %s found, see the warnings above',
            $issueCount,
            $issueCount === 1 ? 'issue' : 'issues'
        ));

        return ExitCode::FAILURE;
    }

    private function reportDeprecatedSkippedClasses(): int
    {
        $deprecatedSkippedClasses = $this->skipper->resolveDeprecatedSkippedClasses();
        if ($deprecatedSkippedClasses === []) {
            return 0;
        }

        $this->rectorStyle->warning(sprintf(
            'These rules are skipped, but are deprecated. Most likely you do not need to skip them anymore, remove them: %s%s',
            "\n\n",
            '* ' . implode("\n* ", $deprecatedSkippedClasses) . "\n"
        ));

        return count($deprecatedSkippedClasses);
    }

    private function reportSetAndRulesDuplicatedRegistrations(): int
    {
        $setAndRulesDuplicatedRegistrations = new Configuration()
            ->getBothSetAndRulesDuplicatedRegistrations();
        if ($setAndRulesDuplicatedRegistrations === []) {
            return 0;
        }

        $this->rectorStyle->warning(sprintf(
            'These rules are registered in both sets and "withRules()". Remove them from "withRules()" to avoid duplications: %s* %s',
            "\n\n",
            implode(' * ', $setAndRulesDuplicatedRegistrations) . "\n"
        ));

        return count($setAndRulesDuplicatedRegistrations);
    }
}
