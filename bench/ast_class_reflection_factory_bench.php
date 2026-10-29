<?php

declare(strict_types=1);

/**
 * Benchmark cached AstClassReflectionFactory::create() calls, including printing and hashing.
 * Parsing, container setup, and the initial reflection build are excluded from timing.
 * This does not measure ChangedNodeScopeRefresher::refresh() or total Rector runtime.
 *
 * The container injects the printer used by the factory on the current branch.
 * PHPStanNodeScopeResolver.php supplies a large class as benchmark input.
 *
 * Run on both branches with the same input file to compare:
 *   php bench/ast_class_reflection_factory_bench.php
 */

use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeTypeResolver\PHPStan\Scope\AstClassReflectionFactory;
use Rector\NodeTypeResolver\PHPStan\Scope\ScopeFactory;

require __DIR__ . '/../tests/bootstrap.php';

const RUNS = 3;
const LOOKUPS = 1000;

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

$astClassReflectionFactory = $rectorConfig->make(AstClassReflectionFactory::class);
$inputFilePath = __DIR__ . '/../src/NodeTypeResolver/PHPStan/Scope/PHPStanNodeScopeResolver.php';
$inputSource = file_get_contents($inputFilePath);
if ($inputSource === false) {
    throw new RuntimeException('Cannot read benchmark input: ' . $inputFilePath);
}

$nodes = (new ParserFactory())->createForNewestSupportedVersion()->parse($inputSource);
$nodes = (new NodeTraverser(new NameResolver()))->traverse($nodes ?? []);
$inputClass = (new NodeFinder())->findFirstInstanceOf($nodes, Class_::class);
if (! $inputClass instanceof Class_) {
    throw new RuntimeException('Benchmark input must contain a class.');
}

$scope = $rectorConfig->make(ScopeFactory::class)
    ->createFromFile($inputFilePath)
    ->enterNamespace('Rector\\NodeTypeResolver\\PHPStan\\Scope');

// Warm the reflection cache before measuring repeated lookups.
$astClassReflectionFactory->create($inputClass, $scope);

$totalMilliseconds = 0.0;
for ($run = 1; $run <= RUNS; ++$run) {
    $start = hrtime(true);
    for ($i = 0; $i < LOOKUPS; ++$i) {
        $astClassReflectionFactory->create($inputClass, $scope);
    }

    $milliseconds = (hrtime(true) - $start) / 1e6;
    $totalMilliseconds += $milliseconds;
    printf("Run %d: %d cached lookups: %.1f ms\n", $run, LOOKUPS, $milliseconds);
}

printf("Average: %.1f ms\n", $totalMilliseconds / RUNS);
