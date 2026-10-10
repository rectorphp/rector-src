<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoScopeFetchBeforeCheapGuardRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\NoScopeFetchBeforeCheapGuardRule;

/**
 * @extends RuleTestCase<NoScopeFetchBeforeCheapGuardRule>
 */
final class NoScopeFetchBeforeCheapGuardRuleTest extends RuleTestCase
{
    public function testScopeFetchBeforeGuard(): void
    {
        $expectedErrorMessage = 'Move the "return null" guard above ScopeFetcher::fetch(), as it does not use the scope - most nodes then bail out before scope resolution.';

        $this->analyse([__DIR__ . '/Source/ScopeFetchBeforeGuardRector.php'], [[$expectedErrorMessage, 15]]);
    }

    public function testSkip(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipScopeFetchRector.php'], []);
    }

    protected function getRule(): Rule
    {
        return new NoScopeFetchBeforeCheapGuardRule();
    }
}
