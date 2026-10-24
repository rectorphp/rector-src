<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoLongInstanceofChainRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\NoLongInstanceofChainRule;

/**
 * @extends RuleTestCase<NoLongInstanceofChainRule>
 */
final class NoLongInstanceofChainRuleTest extends RuleTestCase
{
    public function testLongChain(): void
    {
        $expectedErrorMessage = 'Boolean chain has 8 instanceof checks, more than the allowed 7. Extract it to a helper method, a match(), or a keyed lookup.';

        $this->analyse([__DIR__ . '/Source/LongChain.php'], [[$expectedErrorMessage, 13]]);
    }

    public function testSkipShortChain(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipShortChain.php'], []);
    }

    protected function getRule(): Rule
    {
        return new NoLongInstanceofChainRule();
    }
}
