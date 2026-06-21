<?php

declare(strict_types=1);

namespace Rector\Tests\Reporting;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Configuration\VendorMissAnalyseGuard;
use Rector\Reporting\MissConfigurationReporter;
use Rector\Reporting\UnusedSkipResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Fixture\Element\ThreeMan;
use Rector\Tests\Skipper\Skipper\Source\AnotherClassToSkip;
use Rector\ValueObject\ProcessResult;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MissConfigurationReporterTest extends AbstractLazyTestCase
{
    private const string UNUSED_RULE_MASK = '*/dead-rule/*';

    private const string USED_RULE_MASK = '*/matched-rule/*';

    private const string GLOBAL_MASK = '*/global-mask/*';

    private BufferedOutput $bufferedOutput;

    private MissConfigurationReporter $missConfigurationReporter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bufferedOutput = new BufferedOutput();
        $symfonyStyle = new SymfonyStyle(new ArrayInput([]), $this->bufferedOutput);

        $this->missConfigurationReporter = new MissConfigurationReporter(
            $symfonyStyle,
            new VendorMissAnalyseGuard(),
            $this->make(UnusedSkipResolver::class),
        );

        SimpleParameterProvider::setParameter(Option::SKIP, [
            // skip-everywhere rule skip - forgotten at boot, not trackable, must be excluded
            ThreeMan::class,
            // rule-scoped path skip, never matched - intentional, must be reported even as mask
            FifthElement::class => [self::UNUSED_RULE_MASK],
            // rule-scoped path skip, matched - must not be reported
            AnotherClassToSkip::class => [self::USED_RULE_MASK],
            // global mask path skip - hard to spot, false-positive prone, must not be reported
            self::GLOBAL_MASK,
        ]);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, []);
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, false);
    }

    public function testReportsOnlyTrackableUnusedSkips(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);

        // matched rule-scoped path is marked used scoped to its rule as "class|path"
        $processResult = new ProcessResult([], [], 0, [AnotherClassToSkip::class . '|' . self::USED_RULE_MASK]);
        $this->missConfigurationReporter->reportUnusedSkips($processResult);

        $output = $this->bufferedOutput->fetch();

        // unused rule-scoped path is reported as "rule => path", so both are shown
        $this->assertStringContainsString('dead-rule', $output);
        $this->assertStringContainsString('FifthElement', $output);

        // matched rule-scoped path is excluded
        $this->assertStringNotContainsString('matched-rule', $output);
        $this->assertStringNotContainsString('AnotherClassToSkip', $output);

        // global mask path is excluded (hard to spot, false-positive prone)
        $this->assertStringNotContainsString('global-mask', $output);

        // skip-everywhere rule skip is excluded (not trackable at runtime)
        $this->assertStringNotContainsString('ThreeMan', $output);
    }

    public function testReportsNothingWhenDisabled(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, false);

        $processResult = new ProcessResult([], [], 0, []);
        $this->missConfigurationReporter->reportUnusedSkips($processResult);

        $this->assertSame('', $this->bufferedOutput->fetch());
    }
}
