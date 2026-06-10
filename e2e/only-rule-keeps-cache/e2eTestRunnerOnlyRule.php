#!/usr/bin/env php
<?php

// Tests that a run with --only does not cache files as unchanged.
//
// Step 1: Run with --clear-cache --only RemoveEmptyClassMethodRector → file is clean under
//         that one rule, but must not be cached as clean under all rules
// Step 2: Run without --only → the change pending under RemoveAlwaysTrueIfConditionRector
//         must still be reported

use Rector\Console\Style\SymfonyStyleFactory;
use Rector\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Command\Command;

$projectRoot = __DIR__ . '/..';
$rectorBin = $projectRoot . '/../bin/rector';
$autoloadFile = $projectRoot . '/../vendor/autoload.php';

require_once __DIR__ . '/../../vendor/autoload.php';

$symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
$symfonyStyle = $symfonyStyleFactory->create();

$e2eCommand = 'php ' . $rectorBin . ' process --dry-run --no-ansi -a ' . $autoloadFile;

// Step 1: --only run, file is clean under this one rule
$onlyRule = escapeshellarg('Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector');

$output = [];
exec($e2eCommand . ' --clear-cache --only ' . $onlyRule, $output, $exitCode);
$outputString = trim(implode("\n", $output));

if (! str_contains($outputString, '[OK] Rector is done!')) {
    $symfonyStyle->error('Step 1 failed: Expected no changes under --only RemoveEmptyClassMethodRector');
    $symfonyStyle->writeln($outputString);
    exit(Command::FAILURE);
}

$symfonyStyle->success('Step 1 passed: No changes under --only RemoveEmptyClassMethodRector');

// Step 2: full run without --clear-cache must still report the pending change
$output = [];
exec($e2eCommand, $output, $exitCode);
$outputString = trim(implode("\n", $output));
$outputString = str_replace(__DIR__, '.', $outputString);

$expectedOutput = trim((string) file_get_contents(__DIR__ . '/expected-output.diff'));

if ($outputString === $expectedOutput) {
    $symfonyStyle->success('Step 2 passed: Full run still reports the pending change');
    exit(Command::SUCCESS);
}

$symfonyStyle->error('Step 2 failed: The --only run cached the file as clean, hiding its pending change');
$symfonyStyle->writeln($outputString);
exit(Command::FAILURE);
