<?php

declare(strict_types=1);

use Httpful\Request;
use Nette\Utils\Strings;
use Symfony\Component\Console\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

require __DIR__ . '/../vendor/autoload.php';

final class CleanPhpstanCommand extends Command
{
    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $originalContent = file_get_contents('phpstan.neon');
        $newContent = str_replace('reportUnmatchedIgnoredErrors: false', 'reportUnmatchedIgnoredErrors: true', $originalContent);

        file_put_contents('phpstan.neon', $newContent);

        $process = new Process(['composer', 'phpstan']);
        $process->run();

        $result = $process->getOutput();

        $isFailure = false;
        if (str_contains($result, 'Ignored error pattern')) {
            $isFailure = true;
        }

        file_put_contents('phpstan.neon', $originalContent);

        $output->writeln($result);

        if ($isFailure) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

$command = new CleanPhpstanCommand();

$application = new Application();
$application->add($command);
$application->setDefaultCommand(CommandNaming::classToName($command::class), true);
$application->run();
