<?php

declare(strict_types=1);

/**
 * Benchmark for RemoveDeadInstanceOfAssertRector.
 *
 * The rule removes assert($x instanceof Foo) when $x is already of type Foo. To get the asserted
 * class type it read getType($instanceof->class), which runs the type resolver over a class name
 * node whose name is already known.
 *
 * The change reads a concrete class name straight off the node (new ObjectType($name)); the
 * equals()/isSuperTypeOf() comparison against the expression type is unchanged, so the decision is
 * identical. self/static/parent depend on the enclosing class, so those fall back to getType().
 *
 * This bench parses a file of many functions, each with a typed param and a matching dead
 * instanceof assert, attaches scope (as the real run does), then drives refactor() over every
 * statement-aware node.
 *
 * Run on both branches to compare:
 *   php bench/remove_dead_instanceof_assert_name_type_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Stmt\Function_;
use Rector\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const FUNCTIONS = 150; // functions with a dead instanceof assert
const REPEAT = 60;     // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RemoveDeadInstanceOfAssertRector::class);

$source = "<?php\n\nnamespace Bench;\n\n";
for ($i = 0; $i < FUNCTIONS; ++$i) {
    $source .= "function run{$i}(\\DateTime \$value): void\n{\n";
    $source .= "    assert(\$value instanceof \\DateTime);\n}\n\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var Function_[] $functions */
$functions = $betterNodeFinder->findInstanceOf($stmts, Function_::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $functions): void {
    foreach ($functions as $function) {
        // clone so the removal in one pass does not empty the body for the next repeat
        $rector->refactor(clone $function);
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

$totalCalls = REPEAT * count($functions);

printf('functions: %d, repeats: %d, refactor() calls: %d%s', count($functions), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
