<?php

declare(strict_types=1);

/**
 * Benchmark for AstResolver parsing of reflected class files.
 *
 * Resolves every class under src/ through AstResolver::resolveClassFromName(), which parses
 * and scope-decorates each file once and caches the stmts. The cache is reset between repeats
 * so every repeat re-parses. Measures CPU (user+sys) and the memory retained by the cache.
 *
 * Run on both branches to compare:
 *   php bench/ast_resolver_no_cloning_bench.php
 */

use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\AstResolver;

require __DIR__ . '/../vendor/autoload.php';

const REPEAT = 5;

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var AstResolver $astResolver */
$astResolver = $rectorConfig->make(AstResolver::class);

$classNames = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../src/PhpParser'));
foreach ($iterator as $fileInfo) {
    if ($fileInfo->getExtension() !== 'php') {
        continue;
    }

    $contents = (string) file_get_contents($fileInfo->getPathname());
    if (preg_match('#^namespace (.+);#m', $contents, $namespaceMatch) !== 1) {
        continue;
    }

    if (preg_match('#^(?:final |abstract |readonly )*(?:class|interface|trait|enum) (\w+)#m', $contents, $classMatch) !== 1) {
        continue;
    }

    $classNames[] = $namespaceMatch[1] . '\\' . $classMatch[1];
}

$parsedFileNodesProperty = new ReflectionProperty(AstResolver::class, 'parsedFileNodes');

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($astResolver, $classNames, $parsedFileNodesProperty): int {
    $parsedFileNodesProperty->setValue($astResolver, []);
    gc_collect_cycles();
    $memoryBefore = memory_get_usage();

    $resolved = 0;
    foreach ($classNames as $className) {
        if ($astResolver->resolveClassFromName($className) !== null) {
            ++$resolved;
        }
    }

    gc_collect_cycles();
    $GLOBALS['retainedMemory'] = memory_get_usage() - $memoryBefore;

    return $resolved;
};

// warm up autoloading / reflection, excluded from timing
$resolved = $run();

$cpuStart = $cpuSeconds();
for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;

printf('AstResolver::resolveClassFromName benchmark%s', PHP_EOL);
printf('  classes=%d resolved=%d repeat=%d%s', count($classNames), $resolved, REPEAT, PHP_EOL);
printf('  cpu=%.4fs  retained-cache-memory=%.2f MB  peak=%.2f MB%s', $cpu, $GLOBALS['retainedMemory'] / 1024 / 1024, memory_get_peak_usage() / 1024 / 1024, PHP_EOL);
