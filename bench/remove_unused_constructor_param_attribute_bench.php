<?php

declare(strict_types=1);

/**
 * Benchmark for RemoveUnusedConstructorParamRector.
 *
 * The rule subscribes to Class_. To skip attribute marker classes (whose constructor params are
 * meta, not dead code) it resolved the class reflection and called ClassReflection::isAttributeClass().
 * Whether a class carries #[Attribute] is declared directly on the class, so it is fully visible in
 * the node's attrGroups - no reflection needed.
 *
 * The change reads it off the node (isAttributeClass(Class_)) and runs that check BEFORE
 * resolveClassReflection(). An attribute marker class with a doc-commented constructor now bails
 * before any reflection resolves.
 *
 * This bench parses a file of many attribute marker classes, each with a doc-commented constructor
 * holding an unused param, attaches scope (as the real run does), then drives refactor() over every
 * Class_ node. Each node hits the attribute skip and never changes, so the calls are repeatable.
 *
 * Run on both branches to compare:
 *   php bench/remove_unused_constructor_param_attribute_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Stmt\Class_;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const MARKERS = 150; // attribute marker classes with a doc-commented constructor
const REPEAT = 60;   // outer repeats over the whole class set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RemoveUnusedConstructorParamRector::class);

// generate a file of attribute marker classes, each with a doc-commented constructor whose param
// looks unused - the rule must skip them because they are markers, not dead code
$source = "<?php\n\nnamespace Bench;\n\n";
for ($i = 0; $i < MARKERS; ++$i) {
    $source .= "#[\\Attribute]\nfinal class Marker{$i}\n{\n";
    $source .= "    /**\n     * @param string \$name\n     */\n";
    $source .= "    public function __construct(string \$name)\n    {\n    }\n}\n\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var Class_[] $classes */
$classes = $betterNodeFinder->findInstanceOf($stmts, Class_::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $classes): void {
    foreach ($classes as $class) {
        $rector->refactor($class);
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

$totalCalls = REPEAT * count($classes);

printf('classes: %d, repeats: %d, refactor() calls: %d%s', count($classes), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
