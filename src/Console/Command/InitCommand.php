<?php

declare(strict_types=1);

namespace Rector\Core\Console\Command;

use Rector\Core\Configuration\ConfigInitializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InitCommand extends Command
{
    public function __construct(
        private readonly ConfigInitializer $configInitializer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('init');
        $this->setDescription('Generate rector.php configuration file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->configInitializer->createConfig(getcwd());
        return Command::SUCCESS;
    }
}
