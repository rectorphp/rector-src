<?php

declare(strict_types=1);

/**
 * Benchmark for ClassMethodManipulator::isNamedConstructor().
 *
 * A named constructor is decided by the __construct visibility, and only the protected case also
 * needs the enclosing class final keyword (reflection). The method resolved the class reflection
 * up front for every __construct, before looking at visibility.
 *
 * Visibility is local to the node (isPrivate/isProtected), so the common public and private
 * constructors can be answered with no reflection: public is never a named constructor, private
 * always is. Only a protected constructor still resolves reflection to read isFinalByKeyword().
 *
 * This bench parses a file of classes with ordinary public constructors (the common case),
 * attaches scope (as the real run does), then drives isNamedConstructor() over every __construct.
 * The nodes never change, so the calls are repeatable.
 *
 * Run on both branches to compare:
 *   php bench/named_constructor_visibility_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Stmt\ClassMethod;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeManipulator\ClassMethodManipulator;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;
use Rector\ValueObject\MethodName;

require __DIR__ . '/../vendor/autoload.php';

const CLASSES = 150; // classes with a public constructor (the common case)
const REPEAT = 60;   // outer repeats over the whole constructor set

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$classMethodManipulator = $rectorConfig->make(ClassMethodManipulator::class);

$source = "<?php\n\nnamespace Bench;\n\n";
for ($i = 0; $i < CLASSES; ++$i) {
    $source .= "class SomeService{$i}\n{\n";
    $source .= "    public function __construct(private readonly string \$name)\n    {\n    }\n}\n\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var ClassMethod[] $constructors */
$constructors = array_filter(
    $betterNodeFinder->findInstanceOf($stmts, ClassMethod::class),
    static fn (ClassMethod $classMethod): bool => $classMethod->name->toString() === MethodName::CONSTRUCT
);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($classMethodManipulator, $constructors): void {
    foreach ($constructors as $constructor) {
        $classMethodManipulator->isNamedConstructor($constructor);
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

$totalCalls = REPEAT * count($constructors);

printf('constructors: %d, repeats: %d, isNamedConstructor() calls: %d%s', count($constructors), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
