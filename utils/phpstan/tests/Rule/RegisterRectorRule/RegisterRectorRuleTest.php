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
        $expectedErrorMessage = 'Rector rule "Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\Source\UnregisteredRector" is not registered in any set or level config, so it can never run. Register it in a set/level, or implement ConfigurableRectorInterface if it is meant for downstream configs only.';

        $this->analyse([__DIR__ . '/Source/UnregisteredRector.php'], [[$expectedErrorMessage, 12]]);
    }

    public function testRegistered(): void
    {
        $this->analyse([__DIR__ . '/Source/RegisteredRector.php'], []);
    }

    public function testSkipConfigurable(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipConfigurableRector.php'], []);
    }

    public function testSkipDeprecated(): void
    {
        $this->analyse([__DIR__ . '/Source/SkipDeprecatedRector.php'], []);
    }

    protected function getRule(): Rule
    {
        return new RegisterRectorRule([__DIR__ . '/config']);
    }
}
