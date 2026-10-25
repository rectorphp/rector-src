<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoGetClassReflectionOnScopeRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\NoGetClassReflectionOnScopeRule;

/**
 * @extends RuleTestCase<NoGetClassReflectionOnScopeRule>
 */
final class NoGetClassReflectionOnScopeRuleTest extends RuleTestCase
{
    public function testGetClassReflectionOnScope(): void
    {
        $expectedErrorMessage = 'Do not call getClassReflection() on a Scope. Resolve the class name and fetch the reflection via ReflectionProvider->getClass() instead.';

        $this->analyse([__DIR__ . '/Source/GetClassReflectionOnScope.php'], [[$expectedErrorMessage, 13]]);
    }

    public function testSkipOtherMethodCall(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipOtherMethodCall.php'], []);
    }

    protected function getRule(): Rule
    {
        return new NoGetClassReflectionOnScopeRule();
    }
}
