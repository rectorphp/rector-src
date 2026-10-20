<?php

declare(strict_types=1);

/**
 * Benchmark for StaticPHPUnitEnvironment::isPHPUnitRun().
 *
 * The method answers a per-process constant (PHPUNIT_COMPOSER_INSTALL is defined once at phpunit
 * bootstrap, never after), yet it is called hundreds of thousands of times per Rector run -
 * Skipper::resolveSkippedClasses() alone calls it once per skip resolution. Without memoization
 * each call pays a defined() lookup.
 *
 * Run on both branches to compare:
 *   php bench/phpunit_env_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;

require __DIR__ . '/../vendor/autoload.php';

const CALLS = 255_000;  // calls per run, matching a real run's isPHPUnitRun() volume
const REPEAT = 200;     // outer repeats

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function (): void {
    for ($i = 0; $i < CALLS; ++$i) {
        StaticPHPUnitEnvironment::isPHPUnitRun();
    }
};

// warm up once, excluded from timing
$run();

$cpuStart = $cpuSeconds();
$wallStart = hrtime(true);

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

printf('StaticPHPUnitEnvironment::isPHPUnitRun benchmark%s', PHP_EOL);
printf('  calls=%d repeat=%d%s', CALLS, REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  isPHPUnitRun  cpu=%.4fs  wall=%.4fs%s', $cpuSeconds() - $cpuStart, (hrtime(true) - $wallStart) / 1_000_000_000, PHP_EOL);
