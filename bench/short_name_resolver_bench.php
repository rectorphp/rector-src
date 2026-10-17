<?php

declare(strict_types=1);

/**
 * Benchmark for ShortNameResolver::resolveFromFile() caching.
 *
 * Simulates the file-processing convergence loop: the same file is re-queried across
 * several passes, with its stmts version bumped between passes (as real rules do when
 * they change nodes). Two access patterns are measured:
 *
 *   - single-version: one version, many per-node queries (isolates per-node caching)
 *   - multi-version:   version bumped each pass, many per-node queries per pass
 *                      (this is where the filePath-cached vs version-cached behavior differs)
 *
 * Run on both branches to compare:
 *   php bench/short_name_resolver_bench.php
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

const ITERATIONS = 8;   // convergence passes (stmts version bumps)
const NODES = 400;      // resolveFromFile() calls per pass (one per FullyQualified node)
const REPEAT = 25;      // outer repeats, each with a fresh-cache resolver

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

$sourceFilePath = __DIR__ . '/Source/ManyNamesSource.php';

// shared stateless dependencies; a fresh ShortNameResolver is built per repeat so its cache starts empty
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

/**
 * @param callable(): ShortNameResolver $makeResolver
 */
$run = static function (bool $bumpVersionEachPass) use (
    $makeResolver,
    $testingParser,
    $sourceFilePath
): void {
    $resolver = $makeResolver();
    $file = $testingParser->parseFilePathToFile($sourceFilePath);

    for ($pass = 0; $pass < ITERATIONS; ++$pass) {
        if ($bumpVersionEachPass) {
            // mark the stmts as changed, exactly as a rule that edited the file does
            $file->changeNewStmts($file->getNewStmts());
        }

        for ($node = 0; $node < NODES; ++$node) {
            $resolver->resolveFromFile($file);
        }
    }
};

$measure = static function (bool $bumpVersionEachPass) use ($run, $cpuSeconds): array {
    // warm up autoloading / parser once, excluded from timing
    $run($bumpVersionEachPass);

    $cpuStart = $cpuSeconds();
    $wallStart = hrtime(true);

    for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
        $run($bumpVersionEachPass);
    }

    return [
        'cpu' => $cpuSeconds() - $cpuStart,
        'wall' => (hrtime(true) - $wallStart) / 1_000_000_000,
    ];
};

assert(class_exists(File::class));

$singleVersion = $measure(false);
$multiVersion = $measure(true);

printf('ShortNameResolver::resolveFromFile benchmark%s', PHP_EOL);
printf('  iterations=%d nodes=%d repeat=%d%s', ITERATIONS, NODES, REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  single-version  cpu=%.4fs  wall=%.4fs%s', $singleVersion['cpu'], $singleVersion['wall'], PHP_EOL);
printf('  multi-version   cpu=%.4fs  wall=%.4fs%s', $multiVersion['cpu'], $multiVersion['wall'], PHP_EOL);
