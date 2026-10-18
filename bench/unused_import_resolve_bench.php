<?php

declare(strict_types=1);

/**
 * Benchmark for the UnusedImportRemovingPostRector name-resolution walk (first-pass cost).
 *
 * To find unused `use` imports, enterNode() resolves every referenced name by walking the file
 * stmts. The result is version-cached, so this bench bumps the stmts version before every call,
 * forcing the walk to run each time (no cache hits) - it isolates the raw first-pass cost, which
 * is where merging the former two walks (PHP names + docblock names) into one shows up.
 *
 * Run on both branches to compare:
 *   php bench/unused_import_resolve_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\Node\Stmt\Namespace_;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\FileNode;
use Rector\PostRector\Rector\UnusedImportRemovingPostRector;
use Rector\Testing\TestingParser\TestingParser;
use Rector\ValueObject\Application\File;

require __DIR__ . '/../vendor/autoload.php';

const RESOLVES = 400;   // forced walks per run (version bumped each time)
const REPEAT = 40;      // outer repeats

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var UnusedImportRemovingPostRector $postRector */
$postRector = $rectorConfig->make(UnusedImportRemovingPostRector::class);

$sourceFilePath = __DIR__ . '/Source/ManyNamesSource.php';

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$file = $testingParser->parseFilePathToFile($sourceFilePath);
$postRector->setFile($file);

$fileNode = $file->getFileNode();
assert($fileNode instanceof FileNode);

// the namespaced root that actually carries the use statements
$rootNode = $fileNode;
foreach ($fileNode->stmts as $stmt) {
    if ($stmt instanceof Namespace_) {
        $rootNode = $stmt;
    }
}

$run = static function () use ($postRector, $rootNode, $file): void {
    for ($i = 0; $i < RESOLVES; ++$i) {
        // bump version so the next enterNode misses the cache and does the full walk
        $file->changeNewStmts($file->getNewStmts());
        $postRector->enterNode($rootNode);
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

printf('UnusedImportRemovingPostRector resolve-walk benchmark%s', PHP_EOL);
printf('  resolves=%d repeat=%d%s', RESOLVES, REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  forced walk  cpu=%.4fs  wall=%.4fs%s', $result['cpu'], $result['wall'], PHP_EOL);
