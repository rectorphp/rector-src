<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Contract\Rector\RectorInterface;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Standalone container benchmark: build + resolve, before/after the Illuminate -> entropy swap.
 *
 * Run:  php utils/bench-container.php
 *
 * Boots RectorConfig via LazyContainerFactory, imports a rule-heavy set, then resolves a
 * representative slice of the graph (rules + node resolvers + type mappers + traverser).
 * Reports wall time and peak memory, averaged over fresh container builds.
 */
$iterations = 5;
$setConfig = __DIR__ . '/../config/set/code-quality.php';

// resolve a representative, heavy slice of the graph on each fresh build
$resolve = static function (RectorConfig $rectorConfig): int {
    $count = 0;

    // core services that pull in large subgraphs
    $rectorConfig->make(NodeNameResolver::class);
    $rectorConfig->make(NodeTypeResolver::class);
    $rectorConfig->make(PHPStanStaticTypeMapper::class);
    $rectorConfig->make(RectorNodeTraverser::class);
    $count += 4;

    // every registered rule
    $rectors = $rectorConfig->findByContract(RectorInterface::class);
    foreach ($rectors as $rector) {
        ++$count;
    }

    return $count;
};

$times = [];
$peakMemories = [];
$resolvedCount = 0;

// warm up once (autoloading, opcode) so the first run does not skew the average
$warmupFactory = new LazyContainerFactory();
$warmupConfig = $warmupFactory->create();
$warmupConfig->import($setConfig);
$warmupConfig->boot();
$resolve($warmupConfig);
unset($warmupFactory, $warmupConfig);
RectorConfig::resetRecreated();

for ($i = 0; $i < $iterations; ++$i) {
    gc_collect_cycles();
    $memoryBefore = memory_get_usage();

    $start = hrtime(true);

    $lazyContainerFactory = new LazyContainerFactory();
    $rectorConfig = $lazyContainerFactory->create();
    $rectorConfig->import($setConfig);
    $rectorConfig->boot();

    $resolvedCount = $resolve($rectorConfig);

    $elapsedMs = (hrtime(true) - $start) / 1_000_000;

    $times[] = $elapsedMs;
    $peakMemories[] = memory_get_peak_usage() - $memoryBefore;

    unset($lazyContainerFactory, $rectorConfig);
    RectorConfig::resetRecreated();
}

$average = array_sum($times) / count($times);
sort($times);
$median = $times[intdiv(count($times), 2)];
$min = $times[0];
$max = $times[count($times) - 1];
$peakMemoryMb = (array_sum($peakMemories) / count($peakMemories)) / 1024 / 1024;

echo PHP_EOL;
echo 'Container benchmark' . PHP_EOL;
echo '-------------------' . PHP_EOL;
echo sprintf('iterations:      %d%s', $iterations, PHP_EOL);
echo sprintf('services resolved: %d per build%s', $resolvedCount, PHP_EOL);
echo sprintf('build+resolve avg: %.1f ms%s', $average, PHP_EOL);
echo sprintf('             median: %.1f ms%s', $median, PHP_EOL);
echo sprintf('             min/max: %.1f / %.1f ms%s', $min, $max, PHP_EOL);
echo sprintf('peak memory delta: %.1f MB%s', $peakMemoryMb, PHP_EOL);
echo PHP_EOL;
