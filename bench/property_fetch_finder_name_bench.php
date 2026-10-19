<?php

declare(strict_types=1);

/**
 * Benchmark for the name pre-check in PropertyFetchFinder::findPropertyFetchesInClassLike().
 *
 * When scanning a class body for fetches of one property, each PropertyFetch node ran
 * isInAnonymous() - which calls resolveClassReflection() - before the property name was even
 * compared. A class body references many different properties; every fetch of an unrelated
 * property paid for the anonymous-class reflection lookup only to fail the name check afterwards.
 *
 * The optimization compares the property name first and bails before isInAnonymous() on a
 * mismatch.
 *
 * This bench parses a class whose methods reference many distinct properties (only a few match
 * the target name), attaches scope, then drives findPrivatePropertyFetches() for the target
 * property. The nodes never change, so the calls are repeatable.
 *
 * Run on both branches to compare:
 *   php bench/property_fetch_finder_name_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const METHODS = 40;             // methods, each referencing many distinct properties
const FETCHES_PER_METHOD = 10;  // distinct other-named property fetches per method
const REPEAT = 60;              // outer repeats

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

/** @var PropertyFetchFinder $propertyFetchFinder */
$propertyFetchFinder = $rectorConfig->make(PropertyFetchFinder::class);

// one target property, plus many methods that read unrelated properties $this->aK
$source = "<?php\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
$source .= "    private int \$target = 0;\n\n";
for ($i = 0; $i < METHODS; ++$i) {
    $source .= "    public function run{$i}()\n    {\n        return ";
    $parts = [];
    for ($j = 0; $j < FETCHES_PER_METHOD; ++$j) {
        $parts[] = "\$this->a{$j}";
    }
    $source .= implode(' + ', $parts) . ";\n    }\n\n";
}
$source .= "    public function readTarget()\n    {\n        return \$this->target;\n    }\n}\n";

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var Class_ $class */
$class = $betterNodeFinder->findFirstInstanceOf($stmts, Class_::class);

/** @var Property[] $properties */
$properties = $betterNodeFinder->findInstanceOf($class, Property::class);
$targetProperty = $properties[0];

$scope = ScopeFetcher::fetch($class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($propertyFetchFinder, $class, $targetProperty, $scope): void {
    $propertyFetchFinder->findPrivatePropertyFetches($class, $targetProperty, $scope);
};

// warm up caches once, excluded from timing
$run();

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

@unlink($sourceFilePath);

printf('property fetches scanned: %d, repeats: %d%s', METHODS * FETCHES_PER_METHOD + 1, REPEAT, PHP_EOL);
printf('CPU:  %.4f s  (%.3f ms/scan)%s', $cpu, $cpu / REPEAT * 1000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
