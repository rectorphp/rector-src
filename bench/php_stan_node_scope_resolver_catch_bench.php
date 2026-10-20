<?php

declare(strict_types=1);

/**
 * Benchmark for PHPStanNodeScopeResolver::processNodes() on try/catch heavy code.
 *
 * PHPStan already fills the catch stmts with the catch scope, but every Catch_ callback recursed
 * into processNodes() for the catch stmts again - which also re-ran the decorating node visitors on
 * them. With nested try/catch, each level doubled the work of the levels inside it.
 *
 * The change only processes the catch stmts PHPStan skipped (no matching throw point), after the
 * PHPStan walk, and runs the decorating node visitors once over the whole tree.
 *
 * Run on both branches to compare:
 *   php bench/php_stan_node_scope_resolver_catch_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use Rector\DependencyInjection\LazyContainerFactory;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const REPEAT = 200;

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

$sourceFilePath = __DIR__ . '/Source/NestedTryCatchSource.php';

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

// warm up autoloading / reflection once, excluded from timing
$testingParser->parseFileToDecoratedNodes($sourceFilePath);

$cpuStart = $cpuSeconds();
$wallStart = hrtime(true);

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $testingParser->parseFileToDecoratedNodes($sourceFilePath);
}

printf('PHPStanNodeScopeResolver::processNodes() nested try/catch benchmark%s', PHP_EOL);
printf('  repeat=%d%s', REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  cpu=%.4fs  wall=%.4fs%s', $cpuSeconds() - $cpuStart, (hrtime(true) - $wallStart) / 1_000_000_000, PHP_EOL);
