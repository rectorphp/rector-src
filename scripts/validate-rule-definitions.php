<?php

declare(strict_types=1);

use Rector\Console\ExitCode;
use Rector\Scripts\Finder\RectorClassFinder;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;

require __DIR__ . '/../vendor/autoload.php';

$rectorClassFinder = new RectorClassFinder();
$rectorClasses = $rectorClassFinder->find([
    __DIR__ . '/../rules',
    __DIR__ . '/../vendor/rector/rector-doctrine',
    __DIR__ . '/../vendor/rector/rector-phpunit',
    __DIR__ . '/../vendor/rector/rector-symfony',
    __DIR__ . '/../vendor/rector/rector-downgrade-php',
]);

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
    echo sprintf("Found %d rule definition error(s):\n\n", count($errorMessages));
    foreach ($errorMessages as $errorMessage) {
        echo sprintf("- %s\n", $errorMessage);
    }

    exit(ExitCode::FAILURE);
}

echo sprintf("All %d Rector rule definitions are valid!\n", count($rectorClasses));

exit(ExitCode::SUCCESS);
