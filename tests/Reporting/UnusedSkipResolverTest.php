<?php

declare(strict_types=1);

namespace Rector\Tests\Reporting;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Reporting\UnusedSkipResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Fixture\Element\ThreeMan;
use Rector\Tests\Skipper\Skipper\Source\AnotherClassToSkip;
use Rector\ValueObject\ProcessResult;

final class UnusedSkipResolverTest extends AbstractLazyTestCase
{
    private const string UNUSED_RULE_MASK = '*/dead-rule/*';

    private const string USED_RULE_MASK = '*/matched-rule/*';

    private const string GLOBAL_MASK = '*/global-mask/*';

    private UnusedSkipResolver $unusedSkipResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unusedSkipResolver = $this->make(UnusedSkipResolver::class);

        SimpleParameterProvider::setParameter(Option::SKIP, [
            // skip-everywhere rule skip - forgotten at boot, not trackable, excluded
            ThreeMan::class,
            // rule-scoped path skip, never matched - intentional, reported even as mask
            FifthElement::class => [self::UNUSED_RULE_MASK],
            // rule-scoped path skip, matched - excluded
            AnotherClassToSkip::class => [self::USED_RULE_MASK],
            // global mask path skip - hard to spot, false-positive prone, excluded
            self::GLOBAL_MASK,
        ]);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, []);
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, false);
        SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, false);
        SimpleParameterProvider::setParameter(Option::IS_CACHED_RUN, false);
    }

    public function testResolvesUnusedSkipsAsRuleAndPath(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);

        // used skips are tracked scoped to their rule (class => [path])
        $processResult = new ProcessResult([], [], 0, [
            AnotherClassToSkip::class => [self::USED_RULE_MASK],
        ]);
        $unusedSkips = $this->unusedSkipResolver->resolve($processResult);

        // rule-scoped unused skip is reported as "rule:" with the path nested below
        $this->assertContains(FifthElement::class . ':' . "\n     * " . self::UNUSED_RULE_MASK, $unusedSkips);

        // matched rule-scoped skip, global mask and skip-everywhere rule are excluded
        $this->assertNotContains(AnotherClassToSkip::class . ':' . "\n     * " . self::USED_RULE_MASK, $unusedSkips);
        $this->assertNotContains(self::GLOBAL_MASK, $unusedSkips);
        $this->assertNotContains(ThreeMan::class, $unusedSkips);
    }

    public function testSamePathUnderAnotherRuleDoesNotMarkSkipUsed(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);

        $sharedMask = '*/shared-mask/*';
        SimpleParameterProvider::setParameter(Option::SKIP, [
            // same path skipped under two rules - only the first one actually matched a file
            AnotherClassToSkip::class => [$sharedMask],
            FifthElement::class => [$sharedMask],
        ]);

        // used skip is scoped to its rule, so the shared path stays unused under the other rule
        $processResult = new ProcessResult([], [], 0, [
            AnotherClassToSkip::class => [$sharedMask],
        ]);
        $unusedSkips = $this->unusedSkipResolver->resolve($processResult);

        // the never-matched rule still reports its shared path as unused
        $this->assertContains(FifthElement::class . ':' . "\n     * " . $sharedMask, $unusedSkips);

        // the matched rule is excluded
        $this->assertNotContains(AnotherClassToSkip::class . ':' . "\n     * " . $sharedMask, $unusedSkips);
    }

    public function testReportsUnusedSkipAsRelativePath(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);

        $absolutePath = getcwd() . '/src/Reporting/UnusedSkipResolver.php';
        SimpleParameterProvider::setParameter(Option::SKIP, [
            FifthElement::class => [$absolutePath],
        ]);

        $unusedSkips = $this->unusedSkipResolver->resolve(new ProcessResult([], [], 0, []));

        // the absolute path is shortened to a relative one, matching the "->withSkip()" syntax
        $this->assertContains(
            FifthElement::class . ':' . "\n     * " . 'src/Reporting/UnusedSkipResolver.php',
            $unusedSkips
        );
    }

    public function testGroupsMultipleUnusedPathsUnderRule(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);

        $firstPath = getcwd() . '/src/Reporting/UnusedSkipResolver.php';
        $secondPath = getcwd() . '/src/Reporting/MissConfigurationReporter.php';
        SimpleParameterProvider::setParameter(Option::SKIP, [
            FifthElement::class => [$firstPath, $secondPath],
        ]);

        $unusedSkips = $this->unusedSkipResolver->resolve(new ProcessResult([], [], 0, []));

        // multiple unused paths are grouped under their rule, each nested on its own line
        $this->assertContains(
            FifthElement::class . ':' . "\n     * " . 'src/Reporting/UnusedSkipResolver.php' . "\n     * " . 'src/Reporting/MissConfigurationReporter.php',
            $unusedSkips
        );
    }

    public function testResolvesNothingWhenRunIsNarrowed(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);
        SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, true);

        // narrowed run (cli paths, "--only", "--only-suffix") only touches part of the codebase,
        // so skips outside that scope are not false positives
        $processResult = new ProcessResult([], [], 0, []);

        $this->assertSame([], $this->unusedSkipResolver->resolve($processResult));
    }

    public function testResolvesNothingWhenRunIsCached(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);
        SimpleParameterProvider::setParameter(Option::IS_CACHED_RUN, true);

        // a cached run only re-processes changed files, so skips on cached files never match and
        // are not false positives
        $processResult = new ProcessResult([], [], 0, []);

        $this->assertSame([], $this->unusedSkipResolver->resolve($processResult));
    }

    public function testResolvesNothingWhenDisabled(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, false);

        $processResult = new ProcessResult([], [], 0, []);

        $this->assertSame([], $this->unusedSkipResolver->resolve($processResult));
    }
}
