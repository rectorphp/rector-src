<?php

declare(strict_types=1);

/**
 * Benchmark for NodeNameResolver::isStringName().
 *
 * On a non-matching name (the common case - a rule tests isName against many candidates, most
 * fail) the method falls through to a wildcard-character guard. The array-const + str_contains
 * loop scanned the needle once per wildcard char; a single strpbrk() over the char set does it
 * in one C-level pass with identical throw behaviour.
 *
 * Run on both branches to compare:
 *   php bench/node_name_resolver_is_string_name_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeNameResolver\NodeNameResolver;

require __DIR__ . '/../vendor/autoload.php';

const CALLS = 200_000; // isStringName() calls per run
const REPEAT = 60;     // outer repeats

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var NodeNameResolver $nodeNameResolver */
$nodeNameResolver = $rectorConfig->make(NodeNameResolver::class);

// non-matching, wildcard-free pairs - the hot fall-through path to the guard
$resolved = 'App\\Service\\SomeLongClassName';
$desired = 'App\\Service\\OtherLongClassName';

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($nodeNameResolver, $resolved, $desired): void {
    for ($i = 0; $i < CALLS; ++$i) {
        $nodeNameResolver->isStringName($resolved, $desired);
    }
};

$run(); // warm up, excluded

$cpuStart = $cpuSeconds();
$wallStart = hrtime(true);
for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

printf('NodeNameResolver::isStringName benchmark%s', PHP_EOL);
printf('  calls=%d repeat=%d%s', CALLS, REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  isStringName  cpu=%.4fs  wall=%.4fs%s', $cpuSeconds() - $cpuStart, (hrtime(true) - $wallStart) / 1_000_000_000, PHP_EOL);
