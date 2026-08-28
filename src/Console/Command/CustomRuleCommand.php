<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CustomRuleCommand extends Command
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('custom-rule');
        $this->setDescription('[DEPRECATED] Create base of local custom rule with tests');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->error(
            'The "custom-rule" command is deprecated and no longer generates files. Use an AI agent to scaffold a custom rule instead - it handles the setup faster and with less guesswork.'
        );

        return Command::FAILURE;
    }
}
