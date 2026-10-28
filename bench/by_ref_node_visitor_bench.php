<?php

declare(strict_types=1);

/**
 * Benchmark for ByRefNodeVisitor.
 *
 * The visitor runs inside the decorating traversal, yet it started a nested traversal of its own
 * for every function-like (to mark variables named as a by-ref param or closure use), even when
 * the function-like has no by-ref param or use at all. Every method body was walked twice, and a
 * closure nested N levels deep was walked N + 1 times.
 *
 * The optimization tracks the by-ref variable names per function-like in enterNode()/leaveNode()
 * of the main traversal instead, so every node is visited once.
 *
 * This bench parses a class with methods holding nested closures and arrow functions, a few of them
 * with by-ref params and uses, then traverses it with ByRefNodeVisitor only.
 *
 * Run on both branches to compare:
 *   php bench/by_ref_node_visitor_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\NodeVisitor\ByRefNodeVisitor;

require __DIR__ . '/../vendor/autoload.php';

const METHODS = 60;        // methods, each with nested closures and arrow functions
const REPEAT = 200;        // outer repeats over the whole file

$rectorConfig = new LazyContainerFactory()->create();
$rectorConfig->boot();

$byRefNodeVisitor = $rectorConfig->make(ByRefNodeVisitor::class);

$source = "<?php\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
for ($i = 0; $i < METHODS; ++$i) {
    $byRefParam = $i % 4 === 0 ? '&' : '';

    $source .= "    public function run{$i}(array {$byRefParam}\$items, int \$mode)\n    {\n";
    $source .= "        \$total = 0;\n";
    $source .= "        foreach (\$items as \$key => \$item) {\n";
    $source .= "            \$total += \$this->handle(\$key, \$item, \$mode);\n";
    $source .= "        }\n\n";
    $source .= "        \$mapper = function (array \$values) use (\$mode, &\$total) {\n";
    $source .= "            return array_map(function (\$value) use (\$mode) {\n";
    $source .= "                \$filter = fn (\$inner) => \$inner > \$mode ? \$this->compute(\$inner, \$value) : null;\n";
    $source .= "                return \$filter(\$value) ?? \$this->fallback(\$value, \$mode);\n";
    $source .= "            }, \$values);\n";
    $source .= "        };\n\n";
    $source .= "        \$result = \$mapper(\$items);\n";
    $source .= "        \$items[] = \$total;\n\n";
    $source .= "        return [\$result, \$total, \$items];\n";
    $source .= "    }\n\n";
}

$source .= "}\n";

$stmts = new ParserFactory()->createForHostVersion()->parse($source);

$nodeTraverser = new NodeTraverser($byRefNodeVisitor);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

// warm up once, excluded from timing
$nodeTraverser->traverse($stmts);

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $nodeTraverser->traverse($stmts);
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

printf('methods: %d, repeats: %d%s', METHODS, REPEAT, PHP_EOL);
printf('CPU:  %.4f s  (%.3f ms/traverse)%s', $cpu, $cpu / REPEAT * 1000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
