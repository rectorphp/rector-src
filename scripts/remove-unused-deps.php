<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require __DIR__ . '/../vendor/autoload.php';

$symfonyStyle = new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());

// 1. run the same workflow as CI "Detect composer dependency issues", in machine-readable format
exec(__DIR__ . '/../vendor/bin/composer-dependency-analyser --format junit 2>/dev/null', $outputLines);
$junitXml = implode("\n", $outputLines);

$simpleXml = @simplexml_load_string($junitXml);
if (! $simpleXml instanceof SimpleXMLElement) {
    $symfonyStyle->error('Failed to parse composer-dependency-analyser output');
    exit(1);
}

// 2. collect unused dependencies from the "unused dependencies" testsuite
$unusedDependencies = [];
foreach ($simpleXml->testsuite as $testsuite) {
    if ((string) $testsuite['name'] !== 'unused dependencies') {
        continue;
    }

    foreach ($testsuite->testcase as $testcase) {
        $unusedDependencies[] = (string) $testcase['name'];
    }
}

if ($unusedDependencies === []) {
    $symfonyStyle->success('No unused dependencies found');
    exit(0);
}

$symfonyStyle->listing($unusedDependencies);

// 3. remove unused dependencies from composer.json (composer auto-detects require/require-dev)
//    the autocommit to the branch is handled by the workflow's git-auto-commit-action
foreach ($unusedDependencies as $unusedDependency) {
    exec(sprintf('composer remove %s --no-update --no-interaction 2>&1', escapeshellarg($unusedDependency)));
}

$symfonyStyle->success(sprintf('Removed %d unused dependency(ies) from composer.json', count($unusedDependencies)));
