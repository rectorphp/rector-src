<?php

declare(strict_types=1);

/**
 * Benchmark for ExportToReflectionFunctionRector.
 *
 * The rule subscribes to StaticCall and, once the call is X::export('foo'), decides whether X is
 * ReflectionFunction (or an ancestor) before rewriting to new ReflectionFunction('foo'). It read the
 * caller type with getType($node->class), which runs the type resolver over the class name node even
 * though the name is already on the node.
 *
 * The change reads a concrete class name straight off the node (new ObjectType($name)) and only falls
 * back to getType() for self/static/parent, which depend on the enclosing class. The isSuperTypeOf()
 * check that follows is unchanged, so the decision is identical.
 *
 * This bench parses a file of many \ReflectionFunction::export('foo') calls, attaches scope (as the
 * real run does), then drives refactor() over every StaticCall node.
 *
 * Run on both branches to compare:
 *   php bench/export_to_reflection_function_name_type_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Expr\StaticCall;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\Php74\Rector\StaticCall\ExportToReflectionFunctionRector;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const CALLS = 150; // ReflectionFunction::export() static calls
const REPEAT = 60;  // outer repeats over the whole call set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(ExportToReflectionFunctionRector::class);

$source = "<?php\n\nnamespace Bench;\n\n";
for ($i = 0; $i < CALLS; ++$i) {
    $source .= "\$reflectionFunction{$i} = \\ReflectionFunction::export('foo');\n";
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

printf('static calls: %d, repeats: %d, refactor() calls: %d%s', count($staticCalls), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
