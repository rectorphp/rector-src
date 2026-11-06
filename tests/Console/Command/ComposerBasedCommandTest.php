<?php

declare(strict_types=1);

namespace Rector\Tests\Console\Command;

use PHPUnit\Framework\TestCase;
use Rector\Composer\InstalledPackageResolver;
use Rector\Config\RectorConfig;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Console\Command\ComposerBasedCommand;
use Rector\Console\Style\RectorStyle;
use Rector\Console\Style\RectorStyleFactory;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Console\Command\Source\ComposerBoundRector;

final class ComposerBasedCommandTest extends TestCase
{
    private RectorStyle $rectorStyle;

    protected function setUp(): void
    {
        // the parameter provider is static, reset it between tests
        SimpleParameterProvider::setParameter(Option::COMPOSER_BOUND_RULE_CONFIGURATIONS, []);
    }

    public function testName(): void
    {
        $composerBasedCommand = $this->createComposerBasedCommand([]);

        $this->assertSame('composer-based', $composerBasedCommand->getName());
    }

    public function testSkipWithoutComposerBoundRules(): void
    {
        $composerBasedCommand = $this->createComposerBasedCommand([]);
        $composerBasedCommand->run();

        $this->assertStringContainsString('No composer package bound rule is loaded', $this->rectorStyle->getRecordedOutput());
    }

    public function testActiveRule(): void
    {
        // this project requires PHPUnit
        $composerBoundRector = new ComposerBoundRector('phpunit/phpunit', '>=9.0');

        $composerBasedCommand = $this->createComposerBasedCommand([$composerBoundRector]);
        $composerBasedCommand->run();

        $output = $this->rectorStyle->getRecordedOutput();

        $this->assertStringContainsString('phpunit/phpunit', $output);
        $this->assertStringContainsString('>=9.0', $output);
        $this->assertStringContainsString('1 of 1 composer package bound items are active', $output);
    }

    public function testNotInstalledPackage(): void
    {
        $composerBoundRector = new ComposerBoundRector('not-installed/package', '>=1.0');

        $composerBasedCommand = $this->createComposerBasedCommand([$composerBoundRector]);
        $composerBasedCommand->run();

        $output = $this->rectorStyle->getRecordedOutput();

        $this->assertStringContainsString('not-installed/package', $output);
        $this->assertStringContainsString('0 of 1 composer package bound items are active', $output);
    }

    public function testRuleConfiguration(): void
    {
        $rectorConfig = new RectorConfig();

        // this project requires PHPUnit
        $rectorConfig->ruleWithConfigurationComposerVersionBound(
            RenameClassRector::class,
            [
                'SomeOldClass' => 'SomeNewClass',
            ],
            'phpunit/phpunit',
            '>=9.0'
        );

        $composerBasedCommand = $this->createComposerBasedCommand([]);
        $composerBasedCommand->run();

        $output = $this->rectorStyle->getRecordedOutput();

        $this->assertStringContainsString('Composer package bound rule configuration', $output);
        $this->assertStringContainsString('SomeOldClass: SomeNewClass', $output);
        $this->assertStringContainsString('>=9.0', $output);
    }

    public function testInactiveRuleConfigurationIsReported(): void
    {
        $rectorConfig = new RectorConfig();

        $rectorConfig->ruleWithConfigurationComposerVersionBound(
            RenameClassRector::class,
            [
                'SomeOldClass' => 'SomeNewClass',
            ],
            'not-installed/package',
            '>=1.0'
        );

        $composerBasedCommand = $this->createComposerBasedCommand([]);
        $composerBasedCommand->run();

        $output = $this->rectorStyle->getRecordedOutput();

        $this->assertStringContainsString('not-installed/package', $output);
        $this->assertStringContainsString('0 of 1 composer package bound items are active', $output);
    }

    /**
     * @param ComposerBoundRector[] $rectors
     */
    private function createComposerBasedCommand(array $rectors): ComposerBasedCommand
    {
        $this->rectorStyle = new RectorStyleFactory()->create();
        $this->rectorStyle->startRecording();

        return new ComposerBasedCommand($this->rectorStyle, new InstalledPackageResolver(getcwd()), $rectors);
    }
}
