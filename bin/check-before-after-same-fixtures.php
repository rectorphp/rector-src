<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Finder\Finder;

$invalidFixturePaths = [];

$symfonyStyle = new \Symfony\Component\Console\Style\SymfonyStyle(
    new \Symfony\Component\Console\Input\ArgvInput(),
    new \Symfony\Component\Console\Output\ConsoleOutput()
);

$testFixtureFiles = (new Finder())
    ->files()
    ->in([__DIR__ . '/../tests', __DIR__ . '/../rules-tests'])
    ->name('*.php.inc')
    ->getIterator();

foreach ($testFixtureFiles as $testFixtureFile) {
    if (! str_contains($testFixtureFile->getContents(), '-----')) {
        continue;
    }

    $parts = preg_split('/^\s*-{5,}\s*$/m', file_get_contents($testFixtureFile->getRealPath()));
    if (count($parts) !== 2) {
        continue;
    }

    if (trim($parts[0]) !== trim($parts[1])) {
        continue;
    }

    $invalidFixturePaths[] = $testFixtureFile->getRealPath();
}

if ($invalidFixturePaths === []) {
    $symfonyStyle->success('All fixtures are valid');
    exit(Command::SUCCESS);
}

$symfonyStyle->error('The following fixtures have the same before and after content. Remove the part after "-----" to fix them');

$symfonyStyle->listing($invalidFixturePaths);

exit(Command::FAILURE);
