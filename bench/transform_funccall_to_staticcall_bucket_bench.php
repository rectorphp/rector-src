<?php

declare(strict_types=1);

/**
 * Benchmark for FuncCallToStaticCallRector (representative of the Transform value-object bucket change).
 *
 * The Transform rules (FuncCallToStaticCall, FuncCallToNew, ConstFetchToClassConstFetch,
 * StaticCallToFuncCall, StaticCallToNew, MethodCallToFuncCall, MethodCallToStaticCall) each held a
 * flat list / keyed map and scanned the whole config per visited node, running isName() on every
 * entry. The change buckets the list by lowercased old name once in configure(), so a node only
 * visits the entries sharing its name.
 *
 * This bench drives refactor() over many function calls whose names are NOT in the map (the common
 * case), isolating the scan cost.
 *
 * Run on both branches to compare:
 *   php bench/transform_funccall_to_staticcall_bucket_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Expr\FuncCall;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;

require __DIR__ . '/../vendor/autoload.php';

const RENAMES = 400; // configured func-call-to-static-call entries
const CALLS = 200;   // function calls per pass, names not in the map
const REPEAT = 200;  // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(FuncCallToStaticCallRector::class);

$funcCallsToStaticCalls = [];
for ($i = 0; $i < RENAMES; ++$i) {
    $funcCallsToStaticCalls[] = new FuncCallToStaticCall('old_function_' . $i, 'Bench\\SomeClass' . $i, 'method' . $i);
}

$rector->configure($funcCallsToStaticCalls);

$source = "<?php\n\nnamespace Bench;\n\n";
for ($i = 0; $i < CALLS; ++$i) {
    // names deliberately absent from the configured entries
    $source .= "fresh_call_{$i}();\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var FuncCall[] $funcCalls */
$funcCalls = $betterNodeFinder->findInstanceOf($stmts, FuncCall::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $funcCalls): void {
    foreach ($funcCalls as $funcCall) {
        $rector->refactor($funcCall);
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

$totalCalls = REPEAT * count($funcCalls);

printf('entries: %d, calls: %d, repeats: %d, refactor() calls: %d%s', RENAMES, count($funcCalls), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
