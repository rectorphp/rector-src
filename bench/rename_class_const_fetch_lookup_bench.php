<?php

declare(strict_types=1);

/**
 * Benchmark for RenameClassRector::shouldSkipClassConstFetchForMissingConstantName().
 *
 * For every fully-qualified class-const fetch node the guard used to scan the whole configured
 * rename map, running isName() on each entry to find the one matching the node. Upgrade sets
 * configure thousands of class renames, so this is a per-node linear scan.
 *
 * The change looks the node name up in the map directly (the map is keyed by old class, and a
 * node has a single name), the same hash lookup the rename itself already uses.
 *
 * This bench drives refactor() over many real class-const fetches (\DateTime::ATOM) that are NOT
 * in the rename map - the worst case, where the old code scans every entry before giving up.
 *
 * Run on both branches to compare:
 *   php bench/rename_class_const_fetch_lookup_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Name\FullyQualified;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const RENAMES = 500; // configured class renames, none matching the node below
const FETCHES = 150; // class-const fetches per pass
const REPEAT = 200;  // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RenameClassRector::class);

$configuration = [];
for ($i = 0; $i < RENAMES; ++$i) {
    $configuration['Bench\\SomeOldClass' . $i] = 'Bench\\SomeNewClass' . $i;
}

$rector->configure($configuration);

$source = "<?php\n\nnamespace Bench;\n\n";
for ($i = 0; $i < FETCHES; ++$i) {
    // real class + real constant, class-const fetch, deliberately absent from the rename map
    $source .= "\$x{$i} = \\DateTime::ATOM;\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var FullyQualified[] $fullyQualifieds */
$fullyQualifieds = $betterNodeFinder->findInstanceOf($stmts, FullyQualified::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $fullyQualifieds): void {
    foreach ($fullyQualifieds as $fullyQualified) {
        $rector->refactor($fullyQualified);
    }
};

// warm up autoloading/reflection caches once, excluded from timing
$run();

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

@unlink($sourceFilePath);

$totalCalls = REPEAT * count($fullyQualifieds);

printf('renames: %d, fetches: %d, repeats: %d, refactor() calls: %d%s', RENAMES, count($fullyQualifieds), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
