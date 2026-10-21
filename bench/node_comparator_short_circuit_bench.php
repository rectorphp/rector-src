<?php

declare(strict_types=1);

/**
 * Benchmark for NodeComparator::areNodesEqual() short-circuits.
 *
 * areNodesEqual() is called heavily on the traversal hot path, most often on pairs that are not
 * equal. Previously every single-node pair paid a double printWithoutComments() round-trip
 * (comment removal + printer) even when the answer was trivially false. The change adds two cheap
 * bail-outs before the print: a null operand, and two single nodes of a different class.
 *
 * This bench runs a realistic blend: null pairs and different-class pairs (the cases the
 * short-circuit now skips) mixed with same-class equal/unequal pairs (unchanged - still printed).
 *
 * Run on both branches to compare:
 *   php bench/node_comparator_short_circuit_bench.php
 *
 * Reports CPU time (user+sys); CPU is the reliable metric, wall is noisy under concurrent load.
 */

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Comparing\NodeComparator;

require __DIR__ . '/../vendor/autoload.php';

const REPEAT = 200000;

$rectorConfig = (new LazyContainerFactory())->create();

/** @var NodeComparator $nodeComparator */
$nodeComparator = $rectorConfig->make(NodeComparator::class);

// realistic blend of pairs; most are unequal, the common hot-path case
$pairs = [
    // null operand - newly short-circuited
    [new Variable('value'), null],
    [null, new String_('name')],
    // different class single nodes - newly short-circuited
    [new Variable('value'), new String_('value')],
    [new Identifier('foo'), new LNumber(1)],
    [new String_('a'), new Variable('a')],
    // same class, unequal - unchanged (still printed)
    [new Variable('first'), new Variable('second')],
    [new String_('alpha'), new String_('beta')],
    // same class, equal - unchanged (still printed)
    [new Variable('same'), new Variable('same')],
    [new Identifier('id'), new Identifier('id')],
];

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($nodeComparator, $pairs): void {
    foreach ($pairs as [$first, $second]) {
        $nodeComparator->areNodesEqual($first, $second);
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

$totalCalls = REPEAT * count($pairs);

printf('pairs: %d, repeats: %d, calls: %d%s', count($pairs), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.4f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
