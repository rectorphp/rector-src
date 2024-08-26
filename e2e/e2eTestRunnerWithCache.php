#!/usr/bin/env php
<?php

// runs a rector e2e test.
// checks whether we expect a certain output, or alternatively that rector just processed everything without errors

use Rector\Console\Formatter\ColorConsoleDiffFormatter;
use Rector\Console\Formatter\ConsoleDiffer;
use Rector\Console\Style\SymfonyStyleFactory;
use Rector\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Command\Command;

$projectRoot = __DIR__ .'/..';
$rectorBin = $projectRoot . '/bin/rector';
$autoloadFile = $projectRoot . '/vendor/autoload.php';

// so we can use helper classes here
require_once __DIR__ . '/../vendor/autoload.php';

$e2eCommand = 'php '. $rectorBin .' process --dry-run --no-ansi -a '. $autoloadFile;

exec($e2eCommand, $output, $exitCode);
$output = trim(implode("\n", $output));
$output = str_replace(__DIR__, '.', $output);

$expectedDiff = 'expected-output.diff';
if (isset($argv[1]) && $argv[1] === '--diff') {
    $expectedDiff = $argv[2];
}
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
$consoleDiffer = new ConsoleDiffer(new ColorConsoleDiffFormatter());
$diff = $consoleDiffer->diff($output, $expectedOutput);
$symfonyStyle->writeln($diff);

exit(Command::FAILURE);
