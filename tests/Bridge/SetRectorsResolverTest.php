<?php

declare(strict_types=1);

namespace Rector\Tests\Bridge;

use PHPUnit\Framework\TestCase;
use Rector\Bridge\SetRectorsResolver;
use Rector\Contract\Rector\RectorInterface;
use Rector\Set\ValueObject\SetList;

final class SetRectorsResolverTest extends TestCase
{
    private SetRectorsResolver $setRectorsResolver;

    protected function setUp(): void
    {
        $this->setRectorsResolver = new SetRectorsResolver();
    }

    public function testResolvePhpVersionBasedSet(): void
    {
        $phpVersionBasedSetFilePath = dirname(__DIR__, 2) . '/config/set/php-version-based.php';

        $rectorRulesWithConfiguration = $this->setRectorsResolver->resolveFromFilePathsIncludingConfiguration(
            [$phpVersionBasedSetFilePath]
        );

        $this->assertNotEmpty($rectorRulesWithConfiguration);
        $this->assertContainsOnlyRules($rectorRulesWithConfiguration);
    }

    public function testResolveWithConfiguration(): void
    {
        $rectorRulesWithConfiguration = $this->setRectorsResolver->resolveFromFilePathIncludingConfiguration(
            SetList::PHP_73
        );
        $this->assertCount(10, $rectorRulesWithConfiguration);

        $this->assertArrayHasKey(0, $rectorRulesWithConfiguration);
        $this->assertArrayHasKey(8, $rectorRulesWithConfiguration);

        $this->assertContainsOnlyRules($rectorRulesWithConfiguration);
    }

    /**
     * @param array<int, class-string<RectorInterface>|array<class-string<RectorInterface>, mixed[]>> $rectorRulesWithConfiguration
     */
    private function assertContainsOnlyRules(array $rectorRulesWithConfiguration): void
    {
        foreach ($rectorRulesWithConfiguration as $rectorRuleWithConfiguration) {
            if (is_string($rectorRuleWithConfiguration)) {
                $this->assertTrue(is_a($rectorRuleWithConfiguration, RectorInterface::class, true));
            }

            if (is_array($rectorRuleWithConfiguration)) {
                foreach ($rectorRuleWithConfiguration as $rectorRule => $rectorRuleConfiguration) {
                    $this->assertTrue(is_a($rectorRule, RectorInterface::class, true));
                    $this->assertIsArray($rectorRuleConfiguration);
                }
            }
        }
    }
}
