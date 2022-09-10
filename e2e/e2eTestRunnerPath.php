#!/usr/bin/env php
<?php

// runs a rector e2e test.
// checks whether we expect a certain output, or alternatively that rector just processed everything without errors


$projectRoot = __DIR__ . '/..';
$rectorBin = $projectRoot . '/bin/rector';
$autoloadFile = $projectRoot . '/vendor/autoload.php';

// so we can use helper classes here
require_once __DIR__ . '/../vendor/autoload.php';

$e2eCommand = 'php ' . $rectorBin . ' process --dry-run --no-ansi -a ' . $autoloadFile . ' --clear-cache';

// provide path
if (isset($argv[1]) && ! isset($argv[2])) {
    $e2eCommand .= ' ' . $argv[1];
}

// provide config path
if (isset($argv[1]) && $argv[1] === '-c') {
    $e2eCommand .= ' -c ' . $argv[2];
}

// provide config and path
if (isset($argv[3])) {
    $e2eCommand .= ' ' . $argv[3];
}

exec($e2eCommand, $output, $exitCode);
$output = trim(implode("\n", $output));

echo $output;
exit($exitCode);
