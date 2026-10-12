<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NarrowReturnToUnionParamRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\NarrowReturnToUnionParamRule;

/**
 * @extends RuleTestCase<NarrowReturnToUnionParamRule>
 */
final class NarrowReturnToUnionParamRuleTest extends RuleTestCase
{
    public function testBroadReturnOfUnionParam(): void
    {
        $expectedErrorMessage = 'Return type "PhpParser\Node" is too broad for a returned union param. Declare the explicit union return type "MethodCall|New_|null" instead.';

        $this->analyse([__DIR__ . '/Source/BroadReturnOfUnionParam.php'], [[$expectedErrorMessage, 13]]);
    }

    public function testSkip(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipBroadReturn.php'], []);
    }

    protected function getRule(): Rule
    {
        return new NarrowReturnToUnionParamRule();
    }
}
