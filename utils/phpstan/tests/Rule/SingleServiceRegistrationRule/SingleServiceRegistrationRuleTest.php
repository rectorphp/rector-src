<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\SingleServiceRegistrationRule;

/**
 * @extends RuleTestCase<SingleServiceRegistrationRule>
 */
final class SingleServiceRegistrationRuleTest extends RuleTestCase
{
    public function testDuplicateRegistration(): void
    {
        $this->analyse([__DIR__ . '/Source/DuplicateRegistrationFactory.php'], [
            [
                '"registerTagged(self::SOME_VISITOR_CLASSES, Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule\Source\SomeTagInterface)" is already called on line 22; register the service exactly once.',
                27,
            ],
            [
                '"tag(Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule\Source\SomeOtherService, Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule\Source\SomeTagInterface)" is already called on line 25; register the service exactly once.',
                29,
            ],
            [
                'Service "Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule\Source\SomeResettableService" is registered as singleton and "Rector\Contract\DependencyInjection\ResettableInterface" is autotagged, so this tag() call registers it twice.',
                31,
            ],
        ]);
    }

    public function testSingleRegistration(): void
    {
        $this->analyse([__DIR__ . '/Source/SingleRegistrationFactory.php'], []);
    }

    protected function getRule(): Rule
    {
        return new SingleServiceRegistrationRule();
    }
}
