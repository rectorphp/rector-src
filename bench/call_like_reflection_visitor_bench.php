<?php

declare(strict_types=1);

/**
 * Benchmark for CallLikeReflectionNodeVisitor::enterNode().
 *
 * The visitor decorates call args and runs on every CallLike node during metadata decoration.
 * It resolved the function-like reflection for every call that has arguments, even though that
 * reflection is only used when an argument is a Closure, arrow function, or array literal. A
 * call like strlen($s) or $this->foo($x, $y) - the overwhelming majority - paid for the resolve
 * and then decorated nothing.
 *
 * The optimization adds a cheap node-only pre-check (hasClosureArrowOrArrayArg): if no argument
 * is a Closure/ArrowFunction/Array_, enterNode bails before resolveFunctionLikeReflectionFromCall.
 *
 * This bench parses a file full of ordinary calls (scalar/variable args only), attaches scope,
 * then drives enterNode() over every CallLike node. Those calls decorate nothing, so the calls
 * are repeatable.
 *
 * Run on both branches to compare:
 *   php bench/call_like_reflection_visitor_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\Node\Expr\CallLike;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\NodeVisitor\CallLikeReflectionNodeVisitor;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const METHODS = 60;        // methods, each with a handful of ordinary calls
const REPEAT = 40;         // outer repeats over the whole call set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$visitor = $rectorConfig->make(CallLikeReflectionNodeVisitor::class);

// a class with many methods that make ordinary calls: scalar/variable args, no closures/arrays
$source = "<?php\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
for ($i = 0; $i < METHODS; ++$i) {
    $source .= "    public function run{$i}(string \$value, array \$items)\n    {\n";
    $source .= "        \$length = strlen(\$value);\n";
    $source .= "        \$exists = in_array(\$value, \$items, true);\n";
    $source .= "        \$upper = strtoupper(\$value);\n";
    $source .= "        \$this->helper(\$value, \$length);\n";
    $source .= "        return \$exists;\n";
    $source .= "    }\n\n";
}
$source .= "    public function helper(string \$value, int \$length)\n    {\n    }\n}\n";

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var CallLike[] $callLikes */
$callLikes = $betterNodeFinder->findInstanceOf($stmts, CallLike::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($visitor, $callLikes): void {
    foreach ($callLikes as $callLike) {
        $visitor->enterNode($callLike);
    }
};

// warm up caches once, excluded from timing
$run();

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

@unlink($sourceFilePath);

$totalCalls = REPEAT * count($callLikes);

printf('CallLike nodes: %d, repeats: %d, enterNode() calls: %d%s', count($callLikes), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
