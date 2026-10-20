<?php

declare(strict_types=1);

/**
 * Benchmark for ContextNodeVisitor.
 *
 * The visitor runs inside the decorating traversal, yet it started a nested traversal of its own
 * for every loop/switch (to mark If_/Break_ in the body), every try block (to mark the try stmts)
 * and every attribute (to mark Array_/Closure in its args). Nested loops and try blocks are
 * re-walked once per enclosing loop/try, and each try block built a new NodeTraverser plus an
 * anonymous visitor.
 *
 * The optimization tracks loop/try/attribute depth in enterNode()/leaveNode() of the main
 * traversal instead, so every node is visited once.
 *
 * This bench parses a class with methods full of nested loops, switch, try/catch and attributes,
 * then traverses it with ContextNodeVisitor only.
 *
 * Run on both branches to compare:
 *   php bench/context_node_visitor_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\NodeVisitor\ContextNodeVisitor;

require __DIR__ . '/../vendor/autoload.php';

const METHODS = 60;        // methods, each with nested loops, switch, try/catch
const REPEAT = 200;        // outer repeats over the whole file

$rectorConfig = new LazyContainerFactory()->create();
$rectorConfig->boot();

$contextNodeVisitor = $rectorConfig->make(ContextNodeVisitor::class);

$source = "<?php\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
for ($i = 0; $i < METHODS; ++$i) {
    $source .= "    #[Route(path: '/run{$i}', methods: ['GET', 'POST'], options: ['a' => [1, 2]])]\n";
    $source .= "    public function run{$i}(array \$items, int \$mode)\n    {\n";
    $source .= "        try {\n";
    $source .= "            foreach (\$items as \$key => \$item) {\n";
    $source .= "                foreach (\$item as \$value) {\n";
    $source .= "                    if (\$value === null) {\n";
    $source .= "                        break;\n";
    $source .= "                    }\n";
    $source .= "                    for (\$j = 0; \$j < 3; ++\$j) {\n";
    $source .= "                        switch (\$mode) {\n";
    $source .= "                            case 1:\n";
    $source .= "                                \$this->handle(\$key, \$value, [\$j, \$mode]);\n";
    $source .= "                                break;\n";
    $source .= "                            default:\n";
    $source .= "                                if (\$j > 1) {\n";
    $source .= "                                    break 2;\n";
    $source .= "                                }\n";
    $source .= "                        }\n";
    $source .= "                    }\n";
    $source .= "                }\n";
    $source .= "            }\n";
    $source .= "            try {\n";
    $source .= "                \$result = \$this->compute(\$items[0] ?? null, \$mode + 1);\n";
    $source .= "            } catch (\\RuntimeException \$e) {\n";
    $source .= "                \$result = null;\n";
    $source .= "            }\n";
    $source .= "        } catch (\\Throwable \$throwable) {\n";
    $source .= "            return null;\n";
    $source .= "        } finally {\n";
    $source .= "            \$this->cleanup();\n";
    $source .= "        }\n\n";
    $source .= "        return \$result;\n";
    $source .= "    }\n\n";
}

$source .= "}\n";

$stmts = new ParserFactory()->createForHostVersion()->parse($source);

$nodeTraverser = new NodeTraverser($contextNodeVisitor);

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
