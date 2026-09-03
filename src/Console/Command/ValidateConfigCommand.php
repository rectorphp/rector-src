<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Rector\Console\ExitCode;
use Rector\Reporting\DeprecatedRulesReporter;
use Rector\Reporting\MissConfigurationReporter;
use Rector\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use Rector\ValueObject\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @see \Rector\Tests\Console\Command\ValidateConfigCommandTest
 */
final class ValidateConfigCommand extends Command
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly DeprecatedRulesReporter $deprecatedRulesReporter,
        private readonly MissConfigurationReporter $missConfigurationReporter,
        private readonly SkippedClassResolver $skippedClassResolver,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('validate-config');
        $this->setDescription('Report config hygiene issues without processing any files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $issueCount = 0;

        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedRules();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedSkippedRules();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedCacheMetaExtensions();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedPhpSetsMethods();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedAttributesSetsArgs();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedComposerBasedArgs();
        $issueCount += $this->deprecatedRulesReporter->reportDeprecatedRectorUnsupportedMethods();

        $issueCount += $this->missConfigurationReporter->reportSkippedNeverRegisteredRules();
        $issueCount += $this->missConfigurationReporter->reportSkippedNonRectorClasses();

        $issueCount += $this->reportDeprecatedSkippedClasses();
        $issueCount += $this->reportSetAndRulesDuplicatedRegistrations();

        if ($issueCount === 0) {
            $this->symfonyStyle->success('Config is valid, no issues found');
            return ExitCode::SUCCESS;
        }

        $this->symfonyStyle->error(sprintf(
            '%d config %s found, see the warnings above',
            $issueCount,
            $issueCount === 1 ? 'issue' : 'issues'
        ));

        return ExitCode::FAILURE;
    }

    private function reportDeprecatedSkippedClasses(): int
    {
        $deprecatedSkippedClasses = $this->skippedClassResolver->resolveDeprecatedSkippedClasses();
        if ($deprecatedSkippedClasses === []) {
            return 0;
        }

        $this->symfonyStyle->warning(sprintf(
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

        $this->symfonyStyle->warning(sprintf(
            'These rules are registered in both sets and "withRules()". Remove them from "withRules()" to avoid duplications: %s* %s',
            "\n\n",
            implode(' * ', $setAndRulesDuplicatedRegistrations) . "\n"
        ));

        return count($setAndRulesDuplicatedRegistrations);
    }
}
