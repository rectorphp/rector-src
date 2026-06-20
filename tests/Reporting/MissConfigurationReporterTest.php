<?php

declare(strict_types=1);

namespace Rector\Tests\Reporting;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Configuration\VendorMissAnalyseGuard;
use Rector\Reporting\MissConfigurationReporter;
use Rector\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;
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
    private const UNUSED_GLOB = '*/UnusedGlobMarker/*';

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
            $this->make(SkippedClassResolver::class),
            $this->make(SkippedPathsResolver::class),
        );

        SimpleParameterProvider::setParameter(Option::SKIP, [
            // skip-everywhere rule skip - forgotten at boot, not trackable, must be excluded
            ThreeMan::class,
            // path-scoped class skip, never matched - must be reported
            FifthElement::class => ['*/some/*'],
            // path-scoped class skip, matched - must not be reported
            AnotherClassToSkip::class => ['*/other/*'],
            // glob path skip, never matched - must be reported
            self::UNUSED_GLOB,
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

        $processResult = new ProcessResult([], [], 0, [AnotherClassToSkip::class]);
        $this->missConfigurationReporter->reportUnusedSkips($processResult);

        $output = $this->bufferedOutput->fetch();

        // unused, trackable skips are reported
        $this->assertStringContainsString('FifthElement', $output);
        $this->assertStringContainsString('UnusedGlobMarker', $output);

        // skip-everywhere rule skip is excluded (not trackable at runtime)
        $this->assertStringNotContainsString('ThreeMan', $output);

        // matched skip is excluded
        $this->assertStringNotContainsString('AnotherClassToSkip', $output);
    }

    public function testReportsNothingWhenDisabled(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, false);

        $processResult = new ProcessResult([], [], 0, []);
        $this->missConfigurationReporter->reportUnusedSkips($processResult);

        $this->assertSame('', $this->bufferedOutput->fetch());
    }
}
