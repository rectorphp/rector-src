<?php

declare(strict_types=1);

/**
 * Benchmark for CallLikeReflectionNodeVisitor::enterNode() on calls with array args.
 *
 * The visitor resolved the function-like reflection for every call with an array literal arg,
 * to mark arrays passed to a parameter that cannot hold a Closure (IS_ARG_NOT_ACCEPTING_CLOSURE).
 * That attribute is only read by ArrayToFirstClassCallableRector, which bails on any array that
 * does not have exactly 2 items, so calls like in_array($x, ['a', 'b', 'c']) or
 * $this->foo(['key' => $value]) paid for the resolve and decorated nothing useful.
 *
 * The optimization narrows the node-only pre-check to 2-item arrays (the [$caller, 'method']
 * shape), so other array args bail before resolveFunctionLikeReflectionFromCall.
 *
 * This bench parses a file full of calls with non-callable array args, attaches scope,
 * then drives enterNode() over every CallLike node.
 *
 * Run on both branches to compare:
 *   php bench/call_like_reflection_visitor_array_arg_bench.php
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

const METHODS = 60;        // methods, each with a handful of calls with array args
const REPEAT = 40;         // outer repeats over the whole call set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$visitor = $rectorConfig->make(CallLikeReflectionNodeVisitor::class);

// a class with many methods that make calls with non-callable array args
$source = "<?php\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
for ($i = 0; $i < METHODS; ++$i) {
    $source .= "    public function run{$i}(string \$value, array \$items)\n    {\n";
    $source .= "        \$exists = in_array(\$value, ['a', 'b', 'c'], true);\n";
    $source .= "        \$merged = array_merge(\$items, ['key' => \$value]);\n";
    $source .= "        \$joined = implode(',', [\$value, \$value, \$value]);\n";
    $source .= "        \$this->helper(\$value, []);\n";
    $source .= "        return \$exists;\n";
    $source .= "    }\n\n";
}
$source .= "    public function helper(string \$value, array \$options)\n    {\n    }\n}\n";

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
