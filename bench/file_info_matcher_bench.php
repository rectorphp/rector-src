<?php

declare(strict_types=1);

/**
 * Benchmark for FileInfoMatcher::matchPattern().
 *
 * A skip pattern that is not a literal prefix/suffix and does not fnmatch falls through to
 * matchRealpath(), which calls realpath() on the pattern. PHP does not cache a *failed*
 * realpath() lookup, so a wildcard pattern (realpath = false) pays a filesystem syscall for
 * every single file checked. On a large run that is one failed realpath() per file per pattern.
 *
 * This bench drives the real matchPattern() over many real files with a set of such patterns,
 * so every call reaches the realpath() lookup. The patterns never match, so matchPattern()
 * returns null and does no other work, making the calls repeatable.
 *
 * Run on both branches to compare:
 *   php bench/file_info_matcher_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use Rector\Skipper\Matcher\FileInfoMatcher;

require __DIR__ . '/../vendor/autoload.php';

const REPEAT = 40; // outer repeats over the whole file set

$fileInfoMatcher = new FileInfoMatcher();

// many real files, so each pattern's realpath() lookup is repeated per file within a run
$filePaths = array_values(array_filter(
    glob(__DIR__ . '/../src/*/*.php') ?: [],
    static fn (string $filePath): bool => is_file($filePath)
));

// wildcard patterns whose realpath() is always false (nonexistent dirs) - the uncached hot case
$filePatterns = [
    '*/__no_such_dir_a__/*',
    '*/__no_such_dir_b__/*',
    '*/__no_such_dir_c__/*',
];

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($fileInfoMatcher, $filePaths, $filePatterns): void {
    foreach ($filePaths as $filePath) {
        $fileInfoMatcher->matchPattern($filePath, $filePatterns);
    }
};

$measure = static function () use ($run, $cpuSeconds): array {
    // warm up autoloading once, excluded from timing
    $run();

    $cpuStart = $cpuSeconds();
    $wallStart = hrtime(true);

    for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
        $run();
    }

    return [
        'cpu' => $cpuSeconds() - $cpuStart,
        'wall' => (hrtime(true) - $wallStart) / 1_000_000_000,
    ];
};

$result = $measure();

printf('FileInfoMatcher::matchPattern benchmark%s', PHP_EOL);
printf('  files=%d patterns=%d repeat=%d%s', count($filePaths), count($filePatterns), REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  matchPattern  cpu=%.4fs  wall=%.4fs%s', $result['cpu'], $result['wall'], PHP_EOL);
