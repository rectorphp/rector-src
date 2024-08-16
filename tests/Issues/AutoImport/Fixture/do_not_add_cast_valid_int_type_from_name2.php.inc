<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rector\Tests\Issues\AutoImport\Source\SomeClass;

class DoNotAddCastValidIntTypeFromName2 extends Command
{
    private ?OutputInterface $outputInterface = null;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return SomeClass::zero();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rector\Tests\Issues\AutoImport\Source\SomeClass;

class DoNotAddCastValidIntTypeFromName2 extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return SomeClass::zero();
    }
}

?>
