<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\PreferGenericTemplateOnSameUnionTypeRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\PreferGenericTemplateOnSameUnionTypeRule;

/**
 * @extends RuleTestCase<PreferGenericTemplateOnSameUnionTypeRule>
 */
final class PreferGenericTemplateOnSameUnionTypeRuleTest extends RuleTestCase
{
    public function testSameUnionParamAndReturn(): void
    {
        $expectedErrorMessage = 'Param and return share the same class-type union "ClassMethod|Function_". Add a @template generic to narrow the return type to the passed one.';

        $this->analyse([__DIR__ . '/Source/SameUnionParamAndReturn.php'], [[$expectedErrorMessage, 12]]);
    }

    public function testSkip(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipGenericTemplate.php'], []);
    }

    protected function getRule(): Rule
    {
        return new PreferGenericTemplateOnSameUnionTypeRule();
    }
}
