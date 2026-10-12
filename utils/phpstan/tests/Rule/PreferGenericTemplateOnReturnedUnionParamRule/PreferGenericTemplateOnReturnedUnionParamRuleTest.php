<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\PreferGenericTemplateOnReturnedUnionParamRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\PreferGenericTemplateOnReturnedUnionParamRule;

/**
 * @extends RuleTestCase<PreferGenericTemplateOnReturnedUnionParamRule>
 */
final class PreferGenericTemplateOnReturnedUnionParamRuleTest extends RuleTestCase
{
    public function testBroadReturnOfUnionParam(): void
    {
        $expectedErrorMessage = 'Return type "PhpParser\Node" is broader than the returned union param "MethodCall|New_". Add a @template generic to narrow the return type to the passed one.';

        $this->analyse([__DIR__ . '/Source/BroadReturnOfUnionParam.php'], [[$expectedErrorMessage, 13]]);
    }

    public function testSkip(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipBroadReturn.php'], []);
    }

    protected function getRule(): Rule
    {
        return new PreferGenericTemplateOnReturnedUnionParamRule();
    }
}
