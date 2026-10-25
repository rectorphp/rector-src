<?php

declare(strict_types=1);

/**
 * Benchmark for the "re-offer Class_ after child traversal" change in RectorNodeTraverser.
 *
 * The traverser is pre-order: a Class_ rule runs before the class children are visited, so a rule
 * whose precondition is only met by a descendant removal (e.g. an emptied method body) would miss
 * it in a single pass. The change re-runs the Class_ rules once, after the children settle.
 *
 * The re-offer runs the Class_ rules once more per class. This bench isolates that added cost:
 * a file of many classes, one Class_ rule registered (RemoveEmptyClassMethodRector), each class
 * re-offered once. It is the price of restoring Class_ convergence in a single pass, and is far
 * cheaper than the whole-tree do-while re-traversal it replaces (that re-ran every rule on every
 * node until stable; this re-runs only Class_ rules, only on classes, exactly once).
 *
 * Run on both branches to compare:
 *   php bench/rerun_class_rectors_bench.php
 *
 * Reports CPU time (user+sys); CPU is the reliable metric, wall is noisy under concurrent load.
 */

use Rector\Application\RectorRegistry;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Testing\TestingParser\TestingParser;
use Rector\ValueObject\Application\File;

require __DIR__ . '/../vendor/autoload.php';

const CLASSES = 400; // classes in the source file, each with non-empty methods
const METHODS = 3;   // methods per class
const REPEAT = 200;  // traversals of the whole file

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var RectorRegistry $rectorRegistry */
$rectorRegistry = $rectorConfig->make(RectorRegistry::class);
$rectorRegistry->refreshRectors([$rectorConfig->make(RemoveEmptyClassMethodRector::class)]);

/** @var RectorNodeTraverser $rectorNodeTraverser */
$rectorNodeTraverser = $rectorConfig->make(RectorNodeTraverser::class);

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

// build a file of many classes, every method non-empty so no node is removed;
// the re-offer runs the rule once per class and returns null - pure added overhead
$source = "<?php\n\nnamespace Bench;\n";
for ($c = 0; $c < CLASSES; ++$c) {
    $source .= "\nclass SomeClass{$c}\n{\n";
    for ($m = 0; $m < METHODS; ++$m) {
        $source .= "    public function method{$m}(int \$value): int\n    {\n        return \$value + {$m};\n    }\n";
    }

    $source .= "}\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$file = new File($sourceFilePath, $source);
$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

// warm up caches once, excluded from timing
$rectorNodeTraverser->traverse($stmts, $file);

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $rectorNodeTraverser->traverse($stmts, $file);
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

@unlink($sourceFilePath);

printf('classes: %d, methods/class: %d, traversals: %d%s', CLASSES, METHODS, REPEAT, PHP_EOL);
printf('CPU:  %.4f s  (%.4f ms/traversal)%s', $cpu, $cpu / REPEAT * 1_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
