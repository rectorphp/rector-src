<?php

declare(strict_types=1);

use Rector\Console\ExitCode;
use Rector\Console\Style\RectorStyleFactory;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\Scripts\Finder\RectorClassFinder;

require __DIR__ . '/../vendor/autoload.php';

$rectorStyle = new RectorStyleFactory()->create();

// core rules + all installed rector extensions (rector-doctrine, rector-symfony, rector-phpunit, ...)
$ruleDirectories = array_merge(
    [__DIR__ . '/../rules'],
    glob(__DIR__ . '/../vendor/rector/rector-*', GLOB_ONLYDIR) ?: []
);

$rectorClassFinder = new RectorClassFinder();
$rectorClasses = $rectorClassFinder->find($ruleDirectories);

$errorMessages = [];

foreach ($rectorClasses as $rectorClass) {
    $reflectionClass = new ReflectionClass($rectorClass);

    // rule definition does not depend on constructor dependencies, skip them
    $rector = $reflectionClass->newInstanceWithoutConstructor();
    if (! $rector instanceof DocumentedRuleInterface) {
        continue;
    }

    $ruleDefinition = $rector->getRuleDefinition();

    if (trim($ruleDefinition->getDescription()) === '') {
        $errorMessages[] = sprintf('Rule "%s" is missing a clear description', $rectorClass);
    }

    $codeSamples = $ruleDefinition->getCodeSamples();
    if ($codeSamples === []) {
        $errorMessages[] = sprintf('Rule "%s" is missing at least one code sample', $rectorClass);
        continue;
    }

    foreach ($codeSamples as $codeSample) {
        if (trim($codeSample->getBadCode()) === '' || trim($codeSample->getGoodCode()) === '') {
            $errorMessages[] = sprintf('Rule "%s" has an empty code sample, fill before/after code', $rectorClass);
            break;
        }
    }
}

if ($errorMessages !== []) {
    $rectorStyle->listing($errorMessages);
    $rectorStyle->error(sprintf('Found %d rule definition error(s), see above', count($errorMessages)));

    exit(ExitCode::FAILURE);
}

$rectorStyle->writeln('Scanned paths:');
$rectorStyle->listing(array_map(realpath(...), $ruleDirectories));

$rectorStyle->success(sprintf('All %d Rector rule definitions are valid!', count($rectorClasses)));

exit(ExitCode::SUCCESS);
