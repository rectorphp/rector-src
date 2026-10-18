<?php

declare(strict_types=1);

/**
 * Benchmark for ShortNameResolver::resolveFromFile() traversal cost.
 *
 * resolveFromFile() builds the short-name map by walking the file stmts. The map is
 * version-cached, so during a convergence loop it is rebuilt once per stmts version bump.
 * This bench isolates that rebuild cost: the stmts version is bumped before every call,
 * so every call does the full walk (no cache hits).
 *
 * Use it to compare the single-walk vs double-walk implementations of resolveForStmts():
 *   php bench/short_name_resolver_traversal_bench.php   # on each branch
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\CodingStyle\ClassNameImport\ShortNameResolver;
use Rector\CodingStyle\NodeAnalyzer\UseImportNameMatcher;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;
use Rector\ValueObject\Application\File;

require __DIR__ . '/../vendor/autoload.php';

const REBUILDS = 200;   // forced re-resolves (each one does a full walk)
const REPEAT = 40;      // outer repeats, each with a fresh-cache resolver

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

$sourceFilePath = __DIR__ . '/Source/ManyNamesSource.php';

$simpleCallableNodeTraverser = $rectorConfig->make(SimpleCallableNodeTraverser::class);
$nodeNameResolver = $rectorConfig->make(NodeNameResolver::class);
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);
$useImportNameMatcher = $rectorConfig->make(UseImportNameMatcher::class);
$phpDocInfoFactory = $rectorConfig->make(PhpDocInfoFactory::class);

$makeResolver = static fn (): ShortNameResolver => new ShortNameResolver(
    $simpleCallableNodeTraverser,
    $nodeNameResolver,
    $betterNodeFinder,
    $useImportNameMatcher,
    $phpDocInfoFactory
);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($makeResolver, $testingParser, $sourceFilePath): void {
    $resolver = $makeResolver();
    $file = $testingParser->parseFilePathToFile($sourceFilePath);

    for ($rebuild = 0; $rebuild < REBUILDS; ++$rebuild) {
        // bump version so the next call misses the cache and does the full walk
        $file->changeNewStmts($file->getNewStmts());
        $resolver->resolveFromFile($file);
    }
};

$measure = static function () use ($run, $cpuSeconds): array {
    // warm up autoloading / parser once, excluded from timing
    $run();

    $cpuStart = $cpuSeconds();
    $wallStart = hrtime(true);

    for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
        $run();
    }

    return [
        'cpu' => $cpuSeconds() - $cpuStart,
        'wall' => (hrtime(true) - $wallStart) / 1_000_000_000,
    ];
};

assert(class_exists(File::class));

$result = $measure();

printf('ShortNameResolver::resolveFromFile traversal benchmark%s', PHP_EOL);
printf('  rebuilds=%d repeat=%d%s', REBUILDS, REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  forced re-resolve  cpu=%.4fs  wall=%.4fs%s', $result['cpu'], $result['wall'], PHP_EOL);
