<?php

declare(strict_types=1);

/**
 * Benchmark for AddPropertyTypeDeclarationRector (representative of the Add*Type bucket change).
 *
 * AddParamTypeDeclarationRector / AddPropertyTypeDeclarationRector / AddReturnTypeDeclarationRector
 * each scanned their whole configured list per visited node, running isName()/isStringName() on
 * each entry before the class/object-type check. The change buckets the list by lowercased old
 * name once in configure(), so a node only visits entries sharing its name.
 *
 * This bench drives refactor() over many untyped properties whose names are NOT in the map (the
 * common case), isolating the scan cost.
 *
 * Run on both branches to compare:
 *   php bench/add_property_type_bucket_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Stmt\Property;
use PHPStan\Type\StringType;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

require __DIR__ . '/../vendor/autoload.php';

const ENTRIES = 400;   // configured add-property-type entries
const PROPERTIES = 150; // untyped properties per pass, names not in the map
const REPEAT = 200;    // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(AddPropertyTypeDeclarationRector::class);

$addPropertyTypeDeclarations = [];
for ($i = 0; $i < ENTRIES; ++$i) {
    $addPropertyTypeDeclarations[] = new AddPropertyTypeDeclaration('Bench\\SomeParent' . $i, 'oldProperty' . $i, new StringType());
}

$rector->configure($addPropertyTypeDeclarations);

$source = "<?php\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
for ($i = 0; $i < PROPERTIES; ++$i) {
    // property names deliberately absent from the configured entries
    $source .= "    public \$freshProperty{$i};\n";
}

$source .= "}\n";

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var Property[] $properties */
$properties = $betterNodeFinder->findInstanceOf($stmts, Property::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $properties): void {
    foreach ($properties as $property) {
        $rector->refactor($property);
    }
};

// warm up autoloading/reflection caches once, excluded from timing
$run();

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

@unlink($sourceFilePath);

$totalCalls = REPEAT * count($properties);

printf('entries: %d, properties: %d, repeats: %d, refactor() calls: %d%s', ENTRIES, count($properties), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
