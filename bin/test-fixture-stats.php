<?php

// this is part of downgrade build

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$finder = \Symfony\Component\Finder\Finder::create()
    ->in(__DIR__ . '/../rules-tests')
    ->directories()
    ->name('#Rector$#')
    ->getIterator();

$ruleToFixtureCount = [];

foreach ($finder as $rectorTestDirectory) {
    if ($rectorTestDirectory->getBasename() === 'Rector') {
        continue;
    }

    $fixtureCount = \Symfony\Component\Finder\Finder::create()
        ->files()
        ->name('*.php.inc')
        ->in($rectorTestDirectory->getPathname())
        ->count();

     // very few fixture files, not relevant
     if ($fixtureCount <= 15) {
         continue;
     }

    $ruleToFixtureCount[$rectorTestDirectory->getBasename()] = $fixtureCount;
}

asort($ruleToFixtureCount);

foreach ($ruleToFixtureCount as $rule => $fixtureCount) {
    echo ' * ' . $rule . ': ';
    echo $fixtureCount . PHP_EOL;
}

