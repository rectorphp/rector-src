<?php

declare(strict_types=1);

use Symfony\Component\Console\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

require __DIR__ . '/../vendor/autoload.php';

final class CleanPhpstanCommand extends Command
{
    /**
     * @var string
     */
    private const FILE = 'phpstan.neon';

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! file_exists(self::FILE)) {
            $message = sprintf('File %s does not exists', self::FILE);
            $output->writeln($message);

            return self::FAILURE;
        }

        $originalContent = (string) file_get_contents(self::FILE);
        $newContent = str_replace(
            'reportUnmatchedIgnoredErrors: false',
            'reportUnmatchedIgnoredErrors: true',
            $originalContent
        );

        file_put_contents(self::FILE, $newContent);

        $process = new Process(['composer', 'phpstan']);
        $process->run();

        $result = $process->getOutput();
        $isFailure = str_contains($result, 'Ignored error pattern');

        file_put_contents('phpstan.neon', $originalContent);

        $output->writeln($result);

        return $isFailure ? self::FAILURE : self::SUCCESS;
    }
}

$command = new CleanPhpstanCommand();

$application = new Application();
$application->add($command);
$application->setDefaultCommand(CommandNaming::classToName($command::class), true);
$application->run();
