<?php

// this is part of downgrade build

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Composer\InstalledPackageResolver;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require __DIR__ . '/../vendor/autoload.php';

// 1. find all rector rules in here and in all vendor/rector dirs
$rectorClassFinder = new RectorClassFinder();

$rectorClasses = $rectorClassFinder->find([
    __DIR__ . '/../rules',
    __DIR__ . '/../vendor/rector/rector-doctrine',
    __DIR__ . '/../vendor/rector/rector-phpunit',
    __DIR__ . '/../vendor/rector/rector-symfony',
    __DIR__ . '/../vendor/rector/rector-downgrade-php',
]);

$symfonyStyle = new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());

$symfonyStyle->writeln(sprintf('<fg=green>Found Rector %d rules</>', count($rectorClasses)));


// find set files
new \Nette\Utils\Finder()


final class RectorClassFinder
{
    /**
     * @param string[] $dirs
     * @return array<class-string>
     */
    public function find(array $dirs): array
    {
        $robotLoader = new \Nette\Loaders\RobotLoader();
        $robotLoader->acceptFiles = ['*Rector.php'];
        $robotLoader->addDirectory(__DIR__ . '/../rules');
        $robotLoader->addDirectory(__DIR__ . '/../vendor/rector/rector-doctrine');
        $robotLoader->addDirectory(__DIR__ . '/../vendor/rector/rector-phpunit');
        $robotLoader->addDirectory(__DIR__ . '/../vendor/rector/rector-symfony');
        $robotLoader->addDirectory(__DIR__ . '/../vendor/rector/rector-downgrade-php');


        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/rector-rules');
        $robotLoader->refresh();

        return array_keys($robotLoader->getIndexedClasses());
    }
}
