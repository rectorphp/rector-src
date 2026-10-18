<?php

declare(strict_types=1);

/**
 * Benchmark for UnusedImportRemovingPostRector.
 *
 * To drop unused `use` imports, enterNode() resolves the set of names referenced in a file via
 * two full-AST traversals (findNonUseImportNames() + findNamesInDocBlocks()), and it is not
 * cached. enterNode() fires for both the FileNode root and the inner Namespace_. In a namespaced
 * file the `use` statements live inside the Namespace_, so the FileNode-level call does the full
 * double walk and then finds no `use` to act on - wasted work.
 *
 * This bench drives the real enterNode() on both root nodes a file actually hits. The fixture's
 * imports are all used, so enterNode() reports no change and mutates nothing, making the calls
 * repeatable.
 *
 * Run on both branches to compare:
 *   php bench/unused_import_removing_bench.php
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

const ENTERS = 400;   // enterNode() calls per root node per run
const REPEAT = 40;    // outer repeats

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

$sourceFilePath = __DIR__ . '/Source/ManyNamesSource.php';

/** @var UnusedImportRemovingPostRector $postRector */
$postRector = $rectorConfig->make(UnusedImportRemovingPostRector::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$file = $testingParser->parseFilePathToFile($sourceFilePath);
$postRector->setFile($file);

$fileNode = $file->getFileNode();
assert($fileNode instanceof FileNode);

// the root nodes enterNode() actually hits per file: the FileNode and the inner Namespace_
$rootNodes = [$fileNode];
foreach ($fileNode->stmts as $stmt) {
    if ($stmt instanceof Namespace_) {
        $rootNodes[] = $stmt;
    }
}

$run = static function () use ($postRector, $rootNodes): void {
    for ($enter = 0; $enter < ENTERS; ++$enter) {
        foreach ($rootNodes as $rootNode) {
            $postRector->enterNode($rootNode);
        }
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

printf('UnusedImportRemovingPostRector::enterNode benchmark%s', PHP_EOL);
printf('  root nodes=%d enters=%d repeat=%d%s', count($rootNodes), ENTERS, REPEAT, PHP_EOL);
printf('%s', PHP_EOL);
printf('  enterNode  cpu=%.4fs  wall=%.4fs%s', $result['cpu'], $result['wall'], PHP_EOL);
