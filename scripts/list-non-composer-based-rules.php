<?php

declare(strict_types=1);

use Rector\Console\Style\RectorStyleFactory;
use Rector\Scripts\Finder\RectorClassFinder;
use Rector\Scripts\Finder\RectorSetFilesFinder;
use Rector\Scripts\Resolver\UsedRectorClassResolver;

require __DIR__ . '/../vendor/autoload.php';

// 1. find all rector rules in doctrine, phpunit and symfony packages; core rules are not package-version bound
$rectorClassFinder = new RectorClassFinder();

$rectorClasses = $rectorClassFinder->find([
    __DIR__ . '/../vendor/rector/rector-doctrine',
    __DIR__ . '/../vendor/rector/rector-phpunit',
    __DIR__ . '/../vendor/rector/rector-symfony',
]);

$rectorStyle = new RectorStyleFactory()->create();
$rectorStyle->writeln(sprintf('<fg=green>Found Rector %d rules</>', count($rectorClasses)));

// 2. find "composer-based.php" sets, that bind rules to the installed package version
$rectorSetFilesFinder = new RectorSetFilesFinder();

$rectorSetFiles = $rectorSetFilesFinder->find([
    __DIR__ . '/../vendor/rector/rector-symfony/config/sets',
    __DIR__ . '/../vendor/rector/rector-doctrine/config/sets',
    __DIR__ . '/../vendor/rector/rector-phpunit/config/sets',
]);

$composerBasedSetFiles = array_filter(
    $rectorSetFiles,
    static fn (string $rectorSetFile): bool => basename($rectorSetFile) === 'composer-based.php'
);

$rectorStyle->writeln(sprintf('<fg=green>Found %d composer-based sets</>', count($composerBasedSetFiles)));
$rectorStyle->listing($composerBasedSetFiles);

$usedRectorClassResolver = new UsedRectorClassResolver();
$usedRectorRules = $usedRectorClassResolver->resolve($composerBasedSetFiles);

$rectorStyle->writeln(
    sprintf('<fg=yellow>Found %d Rector rules used in composer-based sets</>', count($usedRectorRules))
);

// these rules are not bound to any package version, so they never belong to a composer-based set
$versionAgnosticSetFileNames = ['code-quality.php', 'typed-collections.php', 'typed-collections-docblocks.php'];

$versionAgnosticSetFiles = array_filter($rectorSetFiles, static fn (string $rectorSetFile): bool => array_any($versionAgnosticSetFileNames, fn (string $versionAgnosticSetFileName): bool => str_ends_with(basename($rectorSetFile), $versionAgnosticSetFileName)));

$versionAgnosticRectorRules = $usedRectorClassResolver->resolve($versionAgnosticSetFiles);

$rectorStyle->writeln(
    sprintf(
        '<fg=yellow>Found %d Rector rules used in version-agnostic sets</>',
        count($versionAgnosticRectorRules)
    )
);

$nonComposerBasedRectorRules = array_diff($rectorClasses, $usedRectorRules, $versionAgnosticRectorRules);

$rectorStyle->newLine();
$rectorStyle->listing($nonComposerBasedRectorRules);

$rectorStyle->writeln(
    sprintf(
        '<fg=yellow;options=bold>Found %d Rector rules not in any composer-based set, likely dead</>',
        count($nonComposerBasedRectorRules)
    )
);
$rectorStyle->newLine();
