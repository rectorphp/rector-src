<?php

declare(strict_types=1);

namespace Rector\Tests\Bridge;

use PHPUnit\Framework\TestCase;
use Rector\Bridge\SetRectorsResolver;
use Rector\Configuration\PhpLevelSetResolver;
use Rector\Contract\Rector\RectorInterface;
use Rector\Php\PhpVersionResolver\ComposerJsonPhpVersionResolver;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

final class SetRectorsResolverTest extends TestCase
{
    private SetRectorsResolver $setRectorsResolver;

    protected function setUp(): void
    {
        $this->setRectorsResolver = new SetRectorsResolver();
    }

    public function testResolveFromFilePathForPhpVersion(): void
    {
        $configFilePaths = PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_70);
        $this->assertCount(6, $configFilePaths);
        $this->assertContainsOnly('string', $configFilePaths);

        foreach ($configFilePaths as $configFilePath) {
            $this->assertFileExists($configFilePath);
        }
    }

    public function testResolveFromFilePathForPhpLevel(): void
    {
        $projectPhpVersion = ComposerJsonPhpVersionResolver::resolve(__DIR__ . '/Fixture/some-composer.json');

        $this->assertIsInt($projectPhpVersion);
        $this->assertSame(PhpVersion::PHP_73, $projectPhpVersion);

        $configFilePaths = PhpLevelSetResolver::resolveFromPhpVersion($projectPhpVersion);
        $this->assertCount(9, $configFilePaths);

        $rectorRulesWithConfiguration = $this->setRectorsResolver->resolveFromFilePathsIncludingConfiguration(
            $configFilePaths
        );
        $this->assertCount(62, $rectorRulesWithConfiguration);
    }

    public function testResolveWithConfiguration(): void
    {
        $rectorRulesWithConfiguration = $this->setRectorsResolver->resolveFromFilePathIncludingConfiguration(
            SetList::PHP_73
        );
        $this->assertCount(9, $rectorRulesWithConfiguration);

        $this->assertArrayHasKey(0, $rectorRulesWithConfiguration);
        $this->assertArrayHasKey(8, $rectorRulesWithConfiguration);

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
