#!/usr/bin/env php
<?php

// runs a rector e2e test.
// checks whether we expect a certain output, or alternatively that rector just processed everything without errors

use Rector\Console\Formatter\ColorConsoleDiffFormatter;
use Rector\Console\Formatter\ConsoleDiffer;
use Rector\Console\Style\SymfonyStyleFactory;
use Rector\Differ\DefaultDiffer;
use Rector\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Command\Command;

$projectRoot = __DIR__ .'/..';
$rectorBin = $projectRoot . '/bin/rector';
$autoloadFile = $projectRoot . '/vendor/autoload.php';

// so we can use helper classes here
require_once __DIR__ . '/../vendor/autoload.php';

$e2eCommand = 'php '. $rectorBin .' process --dry-run --no-ansi -a '. $autoloadFile . ' --clear-cache';

if (isset($argv[1]) && $argv[1] === '-c') {
    $e2eCommand .= ' -c ' . $argv[2];
}

if (isset($argv[1]) && $argv[1] === '--config') {
    $e2eCommand .= ' --config ' . $argv[2];
}

if (isset($argv[1]) && $argv[1] === '-a') {
    $e2eCommand .= ' -a ' . $argv[2];
}

if (isset($argv[1]) && $argv[1] === '--kaizen') {
    $e2eCommand .= ' --kaizen ' . $argv[2];
}

$cliOptions = 'cli-options.txt';
if (file_exists($cliOptions)) {
    $e2eCommand .= ' ' . trim(file_get_contents($cliOptions));
}


exec($e2eCommand, $output, $exitCode);
$output = trim(implode("\n", $output));
$output = str_replace(__DIR__, '.', $output);

$expectedDiff = 'expected-output.diff';
if (!file_exists($expectedDiff)) {
    echo $output;
    exit($exitCode);
}

$symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
$symfonyStyle =  $symfonyStyleFactory->create();

$matchedExpectedOutput = false;
$expectedOutput = trim(file_get_contents($expectedDiff));
if ($output === $expectedOutput) {
    $symfonyStyle->success('End-to-end test successfully completed');
    exit(Command::SUCCESS);
}

// print color diff, to make easy find the differences
$defaultDiffer = new DefaultDiffer();
$colorConsoleDiffFormatter = new ColorConsoleDiffFormatter();
$diff = $colorConsoleDiffFormatter->format($consoleDiffer->diff($output, $expectedOutput));
$symfonyStyle->writeln($diff);

exit(Command::FAILURE);
