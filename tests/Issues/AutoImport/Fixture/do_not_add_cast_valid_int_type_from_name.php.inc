<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoNotAddCastValidIntTypeFromName extends Command
{
    private ?OutputInterface $outputInterface = null;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoNotAddCastValidIntTypeFromName extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}

?>
