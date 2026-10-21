<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoResolveClassReflectionInForeachRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\NoResolveClassReflectionInForeachRule;

/**
 * @extends RuleTestCase<NoResolveClassReflectionInForeachRule>
 */
final class NoResolveClassReflectionInForeachRuleTest extends RuleTestCase
{
    public function testCallInForeach(): void
    {
        $expectedErrorMessage = 'resolveClassReflection() in a foreach() returns the same value every iteration, so the null guard that continues repeats pointlessly. Hoist the call above the loop and return null once.';

        $this->analyse([__DIR__ . '/Source/CallInForeach.php'], [[$expectedErrorMessage, 19]]);
    }

    public function testSkipCallOutsideForeach(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipCallOutsideForeach.php'], []);
    }

    public function testSkipNonRectorClass(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipNonRectorClass.php'], []);
    }

    protected function getRule(): Rule
    {
        return new NoResolveClassReflectionInForeachRule();
    }
}
