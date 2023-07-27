<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class Simulation
{
    public function __construct(
        private OutputInterface $output,
        private readonly Composer $composer,
        private readonly string $targetDirectory,
    ) {
    }
    
    public function prepare(): void
    {
        $exitCode = $composer->install(
            $this->targetDirectory.'/composer.json',
            $this->output,
        );
    }
}

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class Composer
{
    public function install(
        string $composerJson,
        OutputInterface &$output = null,
    ): int {
        $input = new ArrayInput([
            'command' => 'install',
            '--working-dir' => dirname($composerJson),
        ]);
        
        if (null === $output) {
            $output = new BufferedOutput();
        }
        
        $application = new Application();
        $application->setAutoExit(false);

        return $application->run($input, $output);
    }
}
?>
