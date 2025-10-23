<?php

declare(strict_types=1);

use Nette\Loaders\RobotLoader;
use Rector\Bridge\SetRectorsResolver;
use Rector\Scripts\Finder\RectorClassFinder;
use Rector\Scripts\Finder\RectorSetFilesFinder;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

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

$rectorSeFinder = new RectorSetFilesFinder();

$rectorSetFiles = $rectorSeFinder->find([
    __DIR__ . '/../config/set',
    __DIR__ . '/../vendor/rector/rector-symfony/config/sets',
    __DIR__ . '/../vendor/rector/rector-doctrine/config/sets',
    __DIR__ . '/../vendor/rector/rector-phpunit/config/sets',
    __DIR__ . '/../vendor/rector/rector-downgrade-php/config/set',
]);

$symfonyStyle->writeln(sprintf('<fg=green>Found %d sets</>', count($rectorSetFiles)));

$usedRectorClassResolver = new UsedRectorClassResolver();
$usedRectorRules = $usedRectorClassResolver->resolve($rectorSetFiles);

$symfonyStyle->newLine();
$symfonyStyle->writeln(sprintf('<fg=yellow>Found %d used Rector rules in sets</>', count($usedRectorRules)));

$unusedRectorRules = array_diff($rectorClasses, $usedRectorRules);
$symfonyStyle->writeln(
    sprintf('<fg=yellow;options=bold>Found %d Rector rules not in any set</>', count($unusedRectorRules))
);

$symfonyStyle->newLine();
$symfonyStyle->listing($unusedRectorRules);

final class UsedRectorClassResolver
{
    /**
     * @param string[] $rectorSetFiles
     * @return string[]
     */
    public function resolve(array $rectorSetFiles): array
    {
        $setRectorsResolver = new SetRectorsResolver();
        $rulesConfiguration = $setRectorsResolver->resolveFromFilePathsIncludingConfiguration($rectorSetFiles);

        $usedRectorRules = [];
        foreach ($rulesConfiguration as $ruleConfiguration) {
            $usedRectorRules[] = is_string($ruleConfiguration) ? $ruleConfiguration : array_keys($ruleConfiguration)[0];
        }

        sort($usedRectorRules);

        return array_unique($usedRectorRules);
    }
}
