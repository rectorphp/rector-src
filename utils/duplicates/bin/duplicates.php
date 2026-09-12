#!/usr/bin/env php
<?php

declare(strict_types=1);

use Rector\Utils\Duplicates\CloneDetector;
use Symfony\Component\Finder\Finder;

$autoloadFile = __DIR__ . '/../../../vendor/autoload.php';
if (! file_exists($autoloadFile)) {
    fwrite(STDERR, 'Composer autoload not found, run "composer install" first.' . PHP_EOL);
    exit(1);
}

require_once $autoloadFile;

$minLines = 5;
$minTokens = 70;
$fuzzy = false;
$paths = [];

$args = array_slice($argv, 1);
for ($i = 0; $i < count($args); ++$i) {
    $arg = $args[$i];

    if ($arg === '--fuzzy') {
        $fuzzy = true;
    } elseif ($arg === '--min-lines') {
        $minLines = (int) ($args[++$i] ?? $minLines);
    } elseif ($arg === '--min-tokens') {
        $minTokens = (int) ($args[++$i] ?? $minTokens);
    } else {
        $paths[] = $arg;
    }
}

if ($paths === []) {
    fwrite(STDERR, 'Usage: php bin/duplicates.php [--min-lines N] [--min-tokens N] [--fuzzy] <path>...' . PHP_EOL);
    exit(1);
}

$filePaths = [];
foreach ($paths as $path) {
    if (is_file($path)) {
        $filePaths[] = $path;
        continue;
    }

    if (! is_dir($path)) {
        continue;
    }

    $finder = new Finder()
        ->files()
        ->in($path)
        ->name('*.php');

    foreach ($finder as $fileInfo) {
        $realPath = $fileInfo->getRealPath();
        if ($realPath === false) {
            continue;
        }

        $filePaths[] = $realPath;
    }
}

if ($filePaths === []) {
    echo 'No PHP files found in the given paths.' . PHP_EOL;
    exit(0);
}

$cloneDetector = new CloneDetector($minLines, $minTokens, $fuzzy);
$clones = $cloneDetector->detect($filePaths);

if ($clones === []) {
    echo sprintf('[OK] No duplicates found in %d files', count($filePaths)) . PHP_EOL;
    exit(0);
}

$duplicatedLines = 0;
foreach ($clones as $clone) {
    echo sprintf(
        '  - %s:%d-%d (%d lines, %d tokens)',
        $clone->firstFile->filePath,
        $clone->firstFile->startLine,
        $clone->firstFile->endLine,
        $clone->lines,
        $clone->tokens
    ) . PHP_EOL;
    echo sprintf(
        '    %s:%d-%d',
        $clone->secondFile->filePath,
        $clone->secondFile->startLine,
        $clone->secondFile->endLine
    ) . PHP_EOL;
    echo PHP_EOL;

    $duplicatedLines += $clone->lines;
}

fwrite(STDERR, sprintf(
    '[ERROR] Found %d clones with %d duplicated lines in %d scanned files',
    count($clones),
    $duplicatedLines,
    count($filePaths)
) . PHP_EOL);

exit(1);
