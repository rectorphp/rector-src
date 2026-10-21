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
        $expectedErrorMessage = 'resolveClassReflection() is called inside a foreach() loop. The class reflection is the same for every iteration, hoist the call above the loop.';

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
