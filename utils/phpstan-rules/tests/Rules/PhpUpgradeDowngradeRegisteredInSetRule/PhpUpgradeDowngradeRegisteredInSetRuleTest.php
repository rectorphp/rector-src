<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rules\PhpUpgradeDowngradeRegisteredInSetRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rules\PhpUpgradeDowngradeRegisteredInSetRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<PhpUpgradeDowngradeRegisteredInSetRule>
 */
final class PhpUpgradeDowngradeRegisteredInSetRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param array<string|int> $expectedErrorMessagesWithLines
     */
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    /**
     * @return Iterator<string[]|array<int, mixed[]>>
     */
    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/SkipSomePhpFeatureRector.php', []];
        yield [__DIR__ . '/Fixture/SomePhpFeatureRector.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            PhpUpgradeDowngradeRegisteredInSetRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
