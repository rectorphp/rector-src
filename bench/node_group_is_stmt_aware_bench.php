<?php

declare(strict_types=1);

/**
 * Benchmark for NodeGroup::isStmtAwareNode().
 *
 * Called per node on the traversal hot path (top call count in a full profile). It previously used
 * array_any() over the STMTS_AWARE class list with a closure doing a dynamic `$node instanceof $class`
 * per element. The change uses native instanceof via match(true): no per-element closure call, no
 * dynamic instanceof, same subclass semantics.
 *
 * This bench calls it over a realistic node mix - mostly non-stmt-aware nodes (the worst case, which
 * exhausts every instanceof) plus a couple of matches.
 *
 * Run on both branches to compare:
 *   php bench/node_group_is_stmt_aware_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\If_;
use Rector\PhpParser\Enum\NodeGroup;

require __DIR__ . '/../vendor/autoload.php';

const REPEAT = 300000;

$nodes = [];
for ($i = 0; $i < 20; ++$i) {
    $nodes[] = new Variable('x');
    $nodes[] = new String_('s');
    $nodes[] = new Identifier('id');
}

$nodes[] = new ClassMethod('m');
$nodes[] = new If_(new Variable('c'));

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($nodes): void {
    foreach ($nodes as $node) {
        NodeGroup::isStmtAwareNode($node);
    }
};

// warm up
$run();

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

$totalCalls = REPEAT * count($nodes);

printf('nodes: %d, repeats: %d, calls: %d%s', count($nodes), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.4f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
