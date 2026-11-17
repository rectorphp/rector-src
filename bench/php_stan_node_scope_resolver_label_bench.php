<?php

declare(strict_types=1);

/**
 * Benchmark for PHPStanNodeScopeResolver::processNodes() with statements after a goto label.
 *
 * PHPStan skips the statements after a label no goto jumps to, and reports no
 * UnreachableStatementNode for them, so they were left without a scope. The change remembers the
 * stmts of every stmts-aware node that holds a Label, and processes the still unscoped statements
 * after the PHPStan walk.
 *
 * Two cases:
 *   - "no labels": real label-free code, the common case - shows what the per-node label lookup costs
 *   - "labels": dead statements after labels - shows the cost of the extra processing, and that the
 *     statements without a scope drop to 0
 *
 * Run on both branches to compare:
 *   php bench/php_stan_node_scope_resolver_label_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node;
use PhpParser\Node\Stmt;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const REPEAT = 20;

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

$sourceFilePaths = [
    'no labels' => __DIR__ . '/../src/PhpParser/Printer/BetterStandardPrinter.php',
    'labels' => __DIR__ . '/Source/GotoLabelSource.php',
];

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

printf('PHPStanNodeScopeResolver::processNodes() goto label benchmark%s', PHP_EOL);
printf('  repeat=%d%s', REPEAT, PHP_EOL);
printf('%s', PHP_EOL);

foreach ($sourceFilePaths as $case => $sourceFilePath) {
    // warm up autoloading / reflection once, excluded from timing
    $stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

    $stmtCount = 0;
    $unscopedStmtCount = 0;
    SimpleCallableNodeTraverser::traverse($stmts, static function (Node $node) use (
        &$stmtCount,
        &$unscopedStmtCount
    ): null {
        if ($node instanceof Stmt) {
            ++$stmtCount;
            if ($node->getAttribute(AttributeKey::SCOPE) === null) {
                ++$unscopedStmtCount;
            }
        }

        return null;
    });

    $cpuStart = $cpuSeconds();
    $wallStart = hrtime(true);

    for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
        $testingParser->parseFileToDecoratedNodes($sourceFilePath);
    }

    printf(
        '  %-9s  cpu=%.4fs  wall=%.4fs  stmts=%d  without scope=%d%s',
        $case,
        $cpuSeconds() - $cpuStart,
        (hrtime(true) - $wallStart) / 1_000_000_000,
        $stmtCount,
        $unscopedStmtCount,
        PHP_EOL
    );
}
