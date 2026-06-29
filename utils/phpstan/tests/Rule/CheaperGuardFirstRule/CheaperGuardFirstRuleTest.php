<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\CheaperGuardFirstRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\CheaperGuardFirstRule;

/**
 * @extends RuleTestCase<CheaperGuardFirstRule>
 */
final class CheaperGuardFirstRuleTest extends RuleTestCase
{
    public function testExpensiveBeforeCheapGuard(): void
    {
        $expectedErrorMessage = 'Cheap guard on line 32 can run before the expensive call on line 26; move the early return up to bail before the costly analysis.';

        $this->analyse([__DIR__ . '/Source/ExpensiveBeforeCheapGuardRector.php'], [[$expectedErrorMessage, 32]]);
    }

    public function testCheapGuardFirst(): void
    {
        $this->analyse([__DIR__ . '/Source/CheapGuardFirstRector.php'], []);
    }

    public function testDependentGuard(): void
    {
        $this->analyse([__DIR__ . '/Source/DependentGuardRector.php'], []);
    }

    protected function getRule(): Rule
    {
        return new CheaperGuardFirstRule();
    }
}
