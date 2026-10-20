<?php

declare(strict_types=1);

/**
 * Benchmark for AnnotationToAttributeRector::processGenericTags().
 *
 * For every generic doc tag it scans the whole configured annotation list, comparing
 * strtolower($desiredTag) with the tag name. Upgrade sets configure many entries, so this is a
 * per-tag linear scan.
 *
 * This bench drives refactor() over many class methods whose docblock holds a single generic tag
 * that is NOT in the config (the common case). A bare generic tag is not a DoctrineAnnotationTagValueNode,
 * so the processDoctrineAnnotationClasses()/matchAnnotationToAttribute() path is skipped and only the
 * processGenericTags() scan is exercised.
 *
 * Run on both branches to compare:
 *   php bench/annotation_to_attribute_generic_tag_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Stmt\ClassMethod;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const ENTRIES = 400; // configured annotation-to-attribute entries
const METHODS = 100; // class methods with a generic doc tag per pass
const REPEAT = 200;  // outer repeats over the whole set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(AnnotationToAttributeRector::class);

$annotationsToAttributes = [];
for ($i = 0; $i < ENTRIES; ++$i) {
    $annotationsToAttributes[] = new AnnotationToAttribute('Bench\\SomeAnnotation' . $i);
}

$rector->configure($annotationsToAttributes);

$source = "<?php\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
for ($i = 0; $i < METHODS; ++$i) {
    // a bare generic tag deliberately absent from the configured entries
    $source .= "    /**\n     * @freshTag{$i}\n     */\n    public function method{$i}()\n    {\n    }\n\n";
}

$source .= "}\n";

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var ClassMethod[] $classMethods */
$classMethods = $betterNodeFinder->findInstanceOf($stmts, ClassMethod::class);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($rector, $classMethods): void {
    foreach ($classMethods as $classMethod) {
        $rector->refactor($classMethod);
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

$totalCalls = REPEAT * count($classMethods);

printf('entries: %d, methods: %d, repeats: %d, refactor() calls: %d%s', ENTRIES, count($classMethods), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
