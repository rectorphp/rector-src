<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Nette\Utils\Json;
use Rector\Configuration\ConfigDumper;
use Rector\Console\ExitCode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see \Rector\Tests\Configuration\ConfigDumperTest
 */
final class DumpConfigCommand extends Command
{
    public function __construct(
        private readonly ConfigDumper $configDumper
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('dump-config');
        $this->setDescription('Dump the resolved configuration (paths, rules with configuration, skips) as JSON');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(Json::encode($this->configDumper->dump(), pretty: true));

        return ExitCode::SUCCESS;
    }
}
