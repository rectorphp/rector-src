<?php

declare(strict_types=1);

/**
 * Benchmark for the array dim fetch write context decoration.
 *
 * AssignedToNodeVisitor, ByRefNodeVisitor, CallLikeReflectionNodeVisitor and ContextNodeVisitor mark
 * every array dim fetch in a written chain ($items[$key]['k'] in unset(), ++, =&, by-ref foreach,
 * by-ref array item, foreach target, by-ref arg and by-ref return), so ArrayFirstLastRector can skip
 * the inner fetch.
 *
 * This bench parses a class with dim-fetch-heavy methods, then:
 *  - traverses it with the 4 decorating visitors only
 *  - runs ArrayFirstLastRector::refactor() on every ArrayDimFetch
 *
 * Run on both branches to compare:
 *   php bench/array_dim_fetch_write_context_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\NodeTraverser;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\NodeVisitor\AssignedToNodeVisitor;
use Rector\PhpParser\NodeVisitor\ByRefNodeVisitor;
use Rector\PhpParser\NodeVisitor\CallLikeReflectionNodeVisitor;
use Rector\PhpParser\NodeVisitor\ContextNodeVisitor;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const METHODS = 60;   // methods with dim fetch reads and writes
const REPEAT = 200;   // outer repeats over the whole file

$rectorConfig = new LazyContainerFactory()->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$arrayFirstLastRector = $rectorConfig->make(ArrayFirstLastRector::class);

$nodeTraverser = new NodeTraverser(
    $rectorConfig->make(AssignedToNodeVisitor::class),
    $rectorConfig->make(ByRefNodeVisitor::class),
    $rectorConfig->make(CallLikeReflectionNodeVisitor::class),
    $rectorConfig->make(ContextNodeVisitor::class),
);

$source = "<?php\n\nnamespace Bench;\n\nfinal class SomeClass\n{\n";
for ($i = 0; $i < METHODS; ++$i) {
    $source .= <<<PHP
    public function method{$i}(array \$items, array \$other)
    {
        echo \$items[array_key_last(\$items)]['k'];
        echo count(\$items[array_key_first(\$items)]['k']['l']);
        \$items['a']['b']['c'] = \$other['x']['y'];
        ++\$items[array_key_last(\$items)]['k'];
        \$items['a']['b']--;
        unset(\$items[array_key_first(\$items)]['k'], \$items['a']['b']['c']);
        \$list = [&\$items[array_key_last(\$items)]['k'], \$other['z']];
        \$ref = &\$items['a']['b'];
        sort(\$items[array_key_first(\$items)]['k']);
        array_push(\$other['list'], \$items['a']['b']);
        foreach (\$items[array_key_last(\$items)]['k'] as &\$item) {
        }
        foreach (\$other as \$key => \$items['target'][\$key]) {
        }
        foreach (\$other['rows'] as [\$items['a'], \$items['b']['c']]) {
        }
        \$fn = fn &() => \$items[array_key_last(\$items)];
        return \$items[array_key_first(\$items)]['k'];
    }

    public function &byRef{$i}(array \$items)
    {
        return \$items[array_key_last(\$items)]['k'];
    }


PHP;
}

$source .= "}\n";

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var ArrayDimFetch[] $arrayDimFetches */
$arrayDimFetches = $betterNodeFinder->findInstanceOf($stmts, ArrayDimFetch::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$measure = static function (callable $run) use ($cpuSeconds): array {
    // warm up autoloading/reflection caches once, excluded from timing
    $run();

    $wallStart = microtime(true);
    $cpuStart = $cpuSeconds();

    for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
        $run();
    }

    return [$cpuSeconds() - $cpuStart, microtime(true) - $wallStart];
};

[$traverseCpu, $traverseWall] = $measure(static function () use ($nodeTraverser, $stmts): void {
    $nodeTraverser->traverse($stmts);
});

[$refactorCpu, $refactorWall] = $measure(static function () use ($arrayFirstLastRector, $arrayDimFetches): void {
    foreach ($arrayDimFetches as $arrayDimFetch) {
        $arrayFirstLastRector->refactor($arrayDimFetch);
    }
});

@unlink($sourceFilePath);

printf('methods: %d, array dim fetches: %d, repeats: %d%s', METHODS * 2, count($arrayDimFetches), REPEAT, PHP_EOL);
printf('decorating visitors CPU: %.4f s  (%.3f ms/traverse), wall: %.4f s%s', $traverseCpu, $traverseCpu / REPEAT * 1000, $traverseWall, PHP_EOL);
printf('refactor() CPU:          %.4f s  (%.3f us/call), wall: %.4f s%s', $refactorCpu, $refactorCpu / (REPEAT * count($arrayDimFetches)) * 1_000_000, $refactorWall, PHP_EOL);
