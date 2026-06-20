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
    }

    public function testResolvesUnusedSkipsAsRuleAndPath(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, true);

        $processResult = new ProcessResult([], [], 0, [self::USED_RULE_MASK]);
        $unusedSkips = $this->unusedSkipResolver->resolve($processResult);

        // rule-scoped unused skip is reported as "rule => path"
        $this->assertContains(FifthElement::class . ' => ' . self::UNUSED_RULE_MASK, $unusedSkips);

        // matched rule-scoped skip, global mask and skip-everywhere rule are excluded
        $this->assertNotContains(AnotherClassToSkip::class . ' => ' . self::USED_RULE_MASK, $unusedSkips);
        $this->assertNotContains(self::GLOBAL_MASK, $unusedSkips);
        $this->assertNotContains(ThreeMan::class, $unusedSkips);
    }

    public function testResolvesNothingWhenDisabled(): void
    {
        SimpleParameterProvider::setParameter(Option::REPORT_UNUSED_SKIPS, false);

        $processResult = new ProcessResult([], [], 0, []);

        $this->assertSame([], $this->unusedSkipResolver->resolve($processResult));
    }
}
