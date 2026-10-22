<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\RegisterRectorRule;

/**
 * @extends RuleTestCase<RegisterRectorRule>
 */
final class RegisterRectorRuleTest extends RuleTestCase
{
    public function testUnregistered(): void
    {
        $expectedErrorMessage = 'Rector rule "Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\Source\UnregisteredRector" is not registered in any set or level config, so it can never run. Register it in a set/level, or deprecate it if it is no longer wanted.';

        $this->analyse([__DIR__ . '/Source/UnregisteredRector.php'], [[$expectedErrorMessage, 12]]);
    }

    public function testUnregisteredConfigurable(): void
    {
        $expectedErrorMessage = 'Rector rule "Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\Source\UnregisteredConfigurableRector" is not registered in any set or level config, so it can never run. Register it in a set/level, or deprecate it if it is no longer wanted.';

        $this->analyse([__DIR__ . '/Source/UnregisteredConfigurableRector.php'], [[$expectedErrorMessage, 13]]);
    }

    public function testRegistered(): void
    {
        $this->analyse([__DIR__ . '/Source/RegisteredRector.php'], []);
    }

    public function testRegisteredInSiblingConfig(): void
    {
        $this->analyse([__DIR__ . '/Source/SiblingRegisteredRector.php'], []);
    }

    public function testSkipDeprecated(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipDeprecatedRector.php'], []);
    }

    protected function getRule(): Rule
    {
        return new RegisterRectorRule([__DIR__ . '/config', __DIR__ . '/config-sibling']);
    }
}
