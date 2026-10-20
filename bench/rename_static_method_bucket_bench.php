<?php

declare(strict_types=1);

/**
 * Benchmark for RenameStaticMethodRector (representative of the Renaming value-object bucket change).
 *
 * RenameStaticMethodRector / RenameClassConstFetchRector / RenamePropertyRector / RenameAttributeRector
 * each held a flat list of value objects and scanned the whole list per visited node, running
 * isName() on each entry before the object-type check. The change buckets the list by lowercased
 * old name once in configure(), so a node only visits the entries sharing its name.
 *
 * This bench drives refactor() over many static calls whose method names are NOT in the map (the
 * common case), isolating the scan cost from the object-type resolution.
 *
 * Run on both branches to compare:
 *   php bench/rename_static_method_bucket_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Expr\StaticCall;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const RENAMES = 400; // configured static method renames
const CALLS = 200;   // static calls per pass, names not in the map
const REPEAT = 200;  // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RenameStaticMethodRector::class);

$staticMethodRenames = [];
for ($i = 0; $i < RENAMES; ++$i) {
    $staticMethodRenames[] = new RenameStaticMethod('Bench\\SomeClass' . $i, 'oldMethod' . $i, 'Bench\\NewClass' . $i, 'newMethod' . $i);
}

$rector->configure($staticMethodRenames);

$source = "<?php\n\nnamespace Bench;\n\n";
for ($i = 0; $i < CALLS; ++$i) {
    // method names deliberately absent from the configured renames
    $source .= "Other::freshCall{$i}();\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var StaticCall[] $staticCalls */
$staticCalls = $betterNodeFinder->findInstanceOf($stmts, StaticCall::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $staticCalls): void {
    foreach ($staticCalls as $staticCall) {
        $rector->refactor($staticCall);
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

$totalCalls = REPEAT * count($staticCalls);

printf('renames: %d, calls: %d, repeats: %d, refactor() calls: %d%s', RENAMES, count($staticCalls), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
