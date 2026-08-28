<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListRulesCommand extends Command
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('list-rules');
        $this->setDescription('[DEPRECATED] Show loaded Rectors');

        $this->setAliases(['show-rules']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->error(
            'The "list-rules" command is deprecated and no longer provided. Run Rector with the set or rules you desire instead.'
        );

        return Command::FAILURE;
    }
}
