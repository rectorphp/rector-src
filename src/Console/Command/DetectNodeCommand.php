<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @deprecated since 1.1.2 as too sensitive on correct output and unable to click through.
 * Use dynamic, sharedable online version https://getrector.com/ast instead
 */
final class DetectNodeCommand extends Command
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('detect-node');
        $this->setDescription('Detects node for provided PHP content');
        $this->addOption('loop', null, InputOption::VALUE_NONE, 'Keep open so you can try multiple inputs');

        $this->setAliases(['dump-node']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->warning(
            'This rule is deprecated as too sensitive on correct output and unable to click through.
 Use dynamic, shareable online version https://getrector.com/ast instead.'
        );

        return self::SUCCESS;
    }
}
