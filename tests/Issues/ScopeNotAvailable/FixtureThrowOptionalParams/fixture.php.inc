<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureThrowOptionalParams;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $io->writeln('Hello');
        } catch (\Doctrine\DBAL\Exception $e) {
            throw new \Exception((string)$e);
        }
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureThrowOptionalParams;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $io->writeln('Hello');
        } catch (\Doctrine\DBAL\Exception $e) {
            throw new \Exception((string)$e, $e->getCode(), $e);
        }
    }
}

?>
