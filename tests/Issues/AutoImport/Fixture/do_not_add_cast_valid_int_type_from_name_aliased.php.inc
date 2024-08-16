<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rector\Tests\Issues\AutoImport\Source\SomeClass as SomeClass1;
use Fixture\SomeClass;

class DoNotAddCastValidIntTypeFromNameAliased extends Command
{
    private ?OutputInterface $outputInterface = null;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return \Rector\Tests\Issues\AutoImport\Source\SomeClass::zero();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rector\Tests\Issues\AutoImport\Source\SomeClass as SomeClass1;
use Fixture\SomeClass;

class DoNotAddCastValidIntTypeFromNameAliased extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return SomeClass1::zero();
    }
}

?>
