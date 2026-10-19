<?php

declare(strict_types=1);

/**
 * Benchmark for RemoveArgumentFromDefaultParentCallRector.
 *
 * The rule subscribes to Class_. For every class that extends a parent it resolved the class
 * reflection and walked getAncestors() - native reflection over the whole ancestor chain -
 * before even checking whether the class body contains a parent:: call to trim. A subclass
 * that never calls parent:: (the common case) paid for that ancestor lookup only to find
 * nothing to do.
 *
 * The optimization adds a cheap AST pre-scan (hasParentStaticCall): if no method body holds a
 * parent:: static call, the rule bails before resolveClassReflection()/getAncestors().
 *
 * This bench parses a file of many subclasses that extend a parent but never call parent::,
 * attaches scope (as the real run does), then drives refactor() over every Class_ node. Those
 * nodes never change, so the calls are repeatable.
 *
 * Run on both branches to compare:
 *   php bench/remove_argument_from_default_parent_call_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\Node\Stmt\Class_;
use Rector\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const CHILDREN = 150; // subclasses that extend a parent but never call parent::
const REPEAT = 60;    // outer repeats over the whole class set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RemoveArgumentFromDefaultParentCallRector::class);

// generate a file: one parent with a defaulted constructor param, many children that extend it
// but only call an ordinary function - no parent:: call, so the rule has nothing to trim
$source = "<?php\n\nnamespace Bench;\n\n";
$source .= "class SomeParent\n{\n    public function __construct(array \$param = [])\n    {\n    }\n}\n\n";
for ($i = 0; $i < CHILDREN; ++$i) {
    $source .= "class Child{$i} extends \\Bench\\SomeParent\n{\n";
    $source .= "    public function __construct(string \$differentParam)\n    {\n";
    $source .= "        strlen(\$differentParam);\n";
    $source .= "    }\n}\n\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var Class_[] $classes */
$classes = $betterNodeFinder->findInstanceOf($stmts, Class_::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $classes): void {
    foreach ($classes as $class) {
        $rector->refactor($class);
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

$totalCalls = REPEAT * count($classes);

printf('classes: %d, repeats: %d, refactor() calls: %d%s', count($classes), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
