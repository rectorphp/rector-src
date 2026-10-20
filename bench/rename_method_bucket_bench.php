<?php

declare(strict_types=1);

/**
 * Benchmark for RenameMethodRector.
 *
 * The rule is configured with a list of method renames. For every method/static call node it used
 * to scan the whole list, running isStringName() on each entry before the expensive type/reflection
 * checks. With a large configured list (symfony/doctrine sets ship hundreds) this is a per-node
 * linear scan whose cost is pure string comparison self time.
 *
 * The change buckets the renames by lowercased old-method name at configure() time, so a node only
 * looks at the few renames sharing its name - one hash lookup instead of N isStringName() calls.
 *
 * This bench configures the rule with many renames, then drives refactor() over many calls whose
 * names are NOT in the map (the common case), isolating the scan cost from type resolution.
 *
 * Run on both branches to compare:
 *   php bench/rename_method_bucket_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Expr\MethodCall;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const RENAMES = 400; // configured method renames
const CALLS = 200;   // method calls per pass, names not in the map
const REPEAT = 200;  // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RenameMethodRector::class);

$methodCallRenames = [];
for ($i = 0; $i < RENAMES; ++$i) {
    $methodCallRenames[] = new MethodCallRename('Bench\\SomeClass' . $i, 'oldMethod' . $i, 'newMethod' . $i);
}

$rector->configure($methodCallRenames);

$source = "<?php\n\nnamespace Bench;\n\n\$value = new Other();\n";
for ($i = 0; $i < CALLS; ++$i) {
    // names deliberately absent from the configured renames
    $source .= "\$value->freshCall{$i}();\n";
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

printf('renames: %d, calls: %d, repeats: %d, refactor() calls: %d%s', RENAMES, count($methodCalls), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
