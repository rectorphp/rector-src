<?php

declare(strict_types=1);

/**
 * Benchmark for RemoveMethodCallParamRector (representative of the Arguments/Removing bucket change).
 *
 * RemoveMethodCallParamRector / ArgumentAdderRector / ArgumentRemoverRector / RemoveFuncCallRector
 * each scanned their whole configured list per visited node, running isName() on each entry. The
 * change buckets the list by lowercased old method name once in configure() (RemoveFuncCallRector
 * flips to a lowercased name set), so a node only visits entries sharing its name.
 *
 * This bench drives refactor() over many method calls whose names are NOT in the map (the common
 * case), isolating the scan cost.
 *
 * Run on both branches to compare:
 *   php bench/remove_method_call_param_bucket_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Expr\MethodCall;
use Rector\Arguments\Rector\MethodCall\RemoveMethodCallParamRector;
use Rector\Arguments\ValueObject\RemoveMethodCallParam;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const ENTRIES = 400; // configured remove-method-call-param entries
const CALLS = 200;   // method calls per pass, names not in the map
const REPEAT = 200;  // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RemoveMethodCallParamRector::class);

$removeMethodCallParams = [];
for ($i = 0; $i < ENTRIES; ++$i) {
    $removeMethodCallParams[] = new RemoveMethodCallParam('Bench\\SomeClass' . $i, 'oldMethod' . $i, 0);
}

$rector->configure($removeMethodCallParams);

$source = "<?php\n\nnamespace Bench;\n\n\$value = new Other();\n";
for ($i = 0; $i < CALLS; ++$i) {
    // method names deliberately absent from the configured entries
    $source .= "\$value->freshCall{$i}(1, 2);\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var MethodCall[] $methodCalls */
$methodCalls = $betterNodeFinder->findInstanceOf($stmts, MethodCall::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $methodCalls): void {
    foreach ($methodCalls as $methodCall) {
        $rector->refactor($methodCall);
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

$totalCalls = REPEAT * count($methodCalls);

printf('entries: %d, calls: %d, repeats: %d, refactor() calls: %d%s', ENTRIES, count($methodCalls), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
