<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\RegisterRelatedPolyfillRectorRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Utils\PHPStan\Rule\RegisterRelatedPolyfillRectorRule;

/**
 * @extends RuleTestCase<RegisterRelatedPolyfillRectorRule>
 */
final class RegisterRelatedPolyfillRectorRuleTest extends RuleTestCase
{
    public function testUnregistered(): void
    {
        $expectedErrorMessage = 'Class "Rector\Utils\PHPStan\Tests\Rule\RegisterRelatedPolyfillRectorRule\Source\UnregisteredPolyfillRector" implements RelatedPolyfillInterface, but is not registered in config/set/php-polyfills.php. Register it there.';

        $this->analyse([__DIR__ . '/Source/UnregisteredPolyfillRector.php'], [[$expectedErrorMessage, 10]]);
    }

    public function testRegistered(): void
    {
        $this->analyse([__DIR__ . '/Source/RegisteredPolyfillRector.php'], []);
    }

    protected function getRule(): Rule
    {
        return new RegisterRelatedPolyfillRectorRule(__DIR__ . '/config/some_polyfill_set.php');
    }
}
