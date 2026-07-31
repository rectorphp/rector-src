<?php

declare(strict_types=1);

namespace Rector\Tests\Console\Command;

use PHPUnit\Framework\TestCase;
use Rector\Composer\InstalledPackageResolver;
use Rector\Console\Command\ComposerBasedCommand;
use Rector\Tests\Console\Command\Source\ComposerBoundRector;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ComposerBasedCommandTest extends TestCase
{
    private BufferedOutput $bufferedOutput;

    protected function setUp(): void
    {
        $this->bufferedOutput = new BufferedOutput();
    }

    public function testName(): void
    {
        $composerBasedCommand = $this->createComposerBasedCommand([]);

        $this->assertSame('composer-based', $composerBasedCommand->getName());
    }

    public function testSkipWithoutComposerBoundRules(): void
    {
        $composerBasedCommand = $this->createComposerBasedCommand([]);
        $composerBasedCommand->run(new ArrayInput([]), $this->bufferedOutput);

        $this->assertStringContainsString('No composer package bound rule is loaded', $this->bufferedOutput->fetch());
    }

    public function testActiveRule(): void
    {
        // this project requires PHPUnit
        $composerBoundRector = new ComposerBoundRector('phpunit/phpunit', '>=9.0');

        $composerBasedCommand = $this->createComposerBasedCommand([$composerBoundRector]);
        $composerBasedCommand->run(new ArrayInput([]), $this->bufferedOutput);

        $output = $this->bufferedOutput->fetch();

        $this->assertStringContainsString('phpunit/phpunit', $output);
        $this->assertStringContainsString('>=9.0', $output);
        $this->assertStringContainsString('1 of 1 composer package bound rules are active', $output);
    }

    public function testNotInstalledPackage(): void
    {
        $composerBoundRector = new ComposerBoundRector('not-installed/package', '>=1.0');

        $composerBasedCommand = $this->createComposerBasedCommand([$composerBoundRector]);
        $composerBasedCommand->run(new ArrayInput([]), $this->bufferedOutput);

        $output = $this->bufferedOutput->fetch();

        $this->assertStringContainsString('not-installed/package', $output);
        $this->assertStringContainsString('0 of 1 composer package bound rules are active', $output);
    }

    /**
     * @param ComposerBoundRector[] $rectors
     */
    private function createComposerBasedCommand(array $rectors): ComposerBasedCommand
    {
        $symfonyStyle = new SymfonyStyle(new ArrayInput([]), $this->bufferedOutput);

        return new ComposerBasedCommand($symfonyStyle, new InstalledPackageResolver(getcwd()), $rectors);
    }
}
