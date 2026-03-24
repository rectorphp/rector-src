#!/usr/bin/env php
<?php

// Tests that CacheMetaExtensionInterface invalidates cache when the hash changes.
//
// Step 1: Run Rector with enabled.txt=false and --clear-cache → no changes, cache populated
// Step 2: Change enabled.txt to true → cache invalidated → rule triggers → changes reported

use Rector\Console\Formatter\ColorConsoleDiffFormatter;
use Rector\Console\Style\SymfonyStyleFactory;
use Rector\Differ\DefaultDiffer;
use Rector\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Command\Command;

$projectRoot = __DIR__ .'/..';
$rectorBin = $projectRoot . '/../bin/rector';
$autoloadFile = $projectRoot . '/../vendor/autoload.php';

require_once __DIR__ . '/../../vendor/autoload.php';

$symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
$symfonyStyle = $symfonyStyleFactory->create();

$e2eCommand = 'php '. $rectorBin .' process --dry-run --no-ansi -a '. $autoloadFile;

// Step 1: enabled=false, clear cache → no changes
file_put_contents(__DIR__ . '/enabled.txt', "false\n");

$output = [];
exec($e2eCommand . ' --clear-cache', $output, $exitCode);
$outputString = trim(implode("\n", $output));

if (! str_contains($outputString, '[OK] Rector is done!')) {
    $symfonyStyle->error('Step 1 failed: Expected no changes with enabled=false');
    $symfonyStyle->writeln($outputString);
    exit(Command::FAILURE);
}

$symfonyStyle->success('Step 1 passed: No changes with enabled=false');

// Step 2: enabled=true, no --clear-cache → cache meta invalidated → rule triggers
file_put_contents(__DIR__ . '/enabled.txt', "true\n");

$output = [];
exec($e2eCommand, $output, $exitCode);
$outputString = trim(implode("\n", $output));
$outputString = str_replace(__DIR__, '.', $outputString);

$expectedOutput = trim((string) file_get_contents(__DIR__ . '/expected-output.diff'));

// Restore enabled.txt
file_put_contents(__DIR__ . '/enabled.txt', "false\n");

if ($outputString === $expectedOutput) {
    $symfonyStyle->success('Step 2 passed: Cache invalidated, rule triggered');
    exit(Command::SUCCESS);
}

$symfonyStyle->error('Step 2 failed: Expected cache invalidation to trigger the rule');

$defaultDiffer = new DefaultDiffer();
$colorConsoleDiffFormatter = new ColorConsoleDiffFormatter();
$diff = $colorConsoleDiffFormatter->format($defaultDiffer->diff($outputString, $expectedOutput));
$symfonyStyle->writeln($diff);

exit(Command::FAILURE);
