<?php

declare(strict_types=1);

/**
 * Benchmark for the "return type from literal returns" hoist in
 * NumericReturnTypeFromStrictScalarReturnsRector and BoolReturnTypeFromBooleanConstReturnsRector.
 *
 * Both rules subscribe to ClassMethod. They ran the ClassMethodReturnTypeOverrideGuard
 * (shouldSkipClassMethod - parent/override reflection) before checking whether the returns are
 * actually int/float/true/false literals. A method whose single return is not such a literal
 * (the common case) paid for the override lookup only to then fail the literal check.
 *
 * The optimization moves the node-only literal check above shouldSkipClassMethod, so a method
 * that returns a non-literal expression bails before the reflection.
 *
 * This bench parses many methods that return a non-literal expression ($this->helper(...)),
 * attaches scope, then drives refactor() over every ClassMethod. Those nodes never change, so
 * the calls are repeatable.
 *
 * Run on both branches to compare:
 *   php bench/return_type_literal_filter_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\Node\Stmt\ClassMethod;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;

require __DIR__ . '/../vendor/autoload.php';

const CLASSES = 30;         // child classes, each extending a parent
const METHODS_PER_CLASS = 5; // untyped methods returning a non-literal expression
const REPEAT = 50;          // outer repeats over the whole method set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$numericRector = $rectorConfig->make(NumericReturnTypeFromStrictScalarReturnsRector::class);
$boolRector = $rectorConfig->make(BoolReturnTypeFromBooleanConstReturnsRector::class);

// a parent plus children whose methods return a non-literal expression: they pass the
// "single return with expr" gate but are neither int/float nor true/false literals
$source = "<?php\n\nnamespace Bench;\n\n";
$source .= "class SomeParent\n{\n    public function helper(\$value)\n    {\n        return \$value;\n    }\n}\n\n";
for ($i = 0; $i < CLASSES; ++$i) {
    $source .= "class Child{$i} extends \\Bench\\SomeParent\n{\n";
    for ($j = 0; $j < METHODS_PER_CLASS; ++$j) {
        $source .= "    public function run{$j}(\$value)\n    {\n        return \$this->helper(\$value);\n    }\n\n";
    }
    $source .= "}\n\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var ClassMethod[] $classMethods */
$classMethods = $betterNodeFinder->findInstanceOf($stmts, ClassMethod::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$benchRule = static function (object $rector, string $label) use ($classMethods, $cpuSeconds): void {
    $run = static function () use ($rector, $classMethods): void {
        foreach ($classMethods as $classMethod) {
            $rector->refactor($classMethod);
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
    $totalCalls = REPEAT * count($classMethods);

    printf('%s: refactor() calls: %d%s', $label, $totalCalls, PHP_EOL);
    printf('  CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
    printf('  wall: %.4f s%s', $wall, PHP_EOL);
};

printf('methods: %d%s', count($classMethods), PHP_EOL);
$benchRule($numericRector, 'NumericReturnTypeFromStrictScalarReturnsRector');
$benchRule($boolRector, 'BoolReturnTypeFromBooleanConstReturnsRector');

@unlink($sourceFilePath);
