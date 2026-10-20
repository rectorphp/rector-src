<?php

declare(strict_types=1);

/**
 * Benchmark for refreshing the scope of a changed class node (ChangedNodeScopeRefresher::refresh()).
 *
 * When a rule changes a class-like node, its stmts are re-processed with the in-class scope. That
 * scope held the class reflection of the original file, so rules deciding on reflection (e.g. "has
 * this trait already?") did not see their own change and re-applied it on every pass.
 *
 * The change (#8728) rebuilds the class reflection from the changed class node via BetterReflection
 * NodeToReflection + ClassReflectionFactory (AstClassReflectionFactory) and re-enters the class with
 * it, under a fresh cache key so member caches of the former reflection are not reused.
 *
 * This bench parses a file of many classes (parent, interface, constants, properties, methods),
 * attaches scope (as the real run does), then drives refresh() over every class node - the exact
 * path RectorRunner takes after a rule returns a changed Class_.
 *
 * By default the classes are refreshed with the same content on every pass, as when a rule keeps
 * visiting a class it does not change further. With "changed", every refresh toggles an extra
 * constant first, so each refresh sees a class content different from the former one.
 *
 * Run on both branches to compare:
 *   php bench/class_reflection_refresh_bench.php
 *   php bench/class_reflection_refresh_bench.php changed
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy under
 * concurrent load.
 */

use PhpParser\Node\Const_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\MutatingScope;
use Rector\Application\ChangedNodeScopeRefresher;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const CLASSES = 40;  // classes in the parsed file
const METHODS = 10;  // methods per class
const REPEAT = 20;   // outer repeats over the whole set

$isChanged = ($argv[1] ?? '') === 'changed';

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

/** @var ChangedNodeScopeRefresher $changedNodeScopeRefresher */
$changedNodeScopeRefresher = $rectorConfig->make(ChangedNodeScopeRefresher::class);

$source = "<?php\n\ndeclare(strict_types=1);\n\nnamespace Bench;\n\n";
$source .= "interface SomeContract\n{\n    public function handle(int \$value): int;\n}\n\n";
$source .= "abstract class SomeParent\n{\n    protected array \$items = [];\n\n";
$source .= "    public function parentMethod(string \$name): string\n    {\n        return strtoupper(\$name);\n    }\n}\n\n";

for ($i = 0; $i < CLASSES; ++$i) {
    $source .= "final class Service{$i} extends SomeParent implements SomeContract\n{\n";
    $source .= "    public const NAME = 'service{$i}';\n\n";
    $source .= "    private int \$counter = 0;\n\n";
    $source .= "    private ?\\DateTimeImmutable \$createdAt = null;\n\n";
    $source .= "    public function handle(int \$value): int\n    {\n        return \$this->counter += \$value;\n    }\n\n";

    for ($j = 0; $j < METHODS; ++$j) {
        $source .= "    public function method{$j}(string \$name): string\n    {\n";
        $source .= "        \$this->items[] = \$name;\n";
        $source .= "        return \$this->parentMethod(\$name) . self::NAME . \$this->handle({$j});\n    }\n\n";
    }

    $source .= "}\n\n";
}

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$stmts = $testingParser->parseFileToDecoratedNodes($sourceFilePath);

/** @var Class_[] $classes */
$classes = array_values(array_filter(
    $betterNodeFinder->findInstanceOf($stmts, Class_::class),
    static fn (Class_ $class): bool => $class->isFinal()
));

// keep the original in-class scope, as RectorRunner passes the scope of the node before the change
$scopes = [];
foreach ($classes as $key => $class) {
    $scope = $class->getAttribute(AttributeKey::SCOPE);
    if (! $scope instanceof MutatingScope || ! $scope->isInClass()) {
        fwrite(STDERR, 'Class node is missing the in-class scope, bench would not hit the refresh path' . PHP_EOL);
        exit(1);
    }

    $scopes[$key] = $scope;
}

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($changedNodeScopeRefresher, $classes, $scopes, $sourceFilePath, $isChanged): void {
    foreach ($classes as $key => $class) {
        if ($isChanged) {
            if ($class->stmts[0] instanceof ClassConst && $class->stmts[0]->consts[0]->name->toString() === 'EXTRA') {
                array_shift($class->stmts);
            } else {
                // BetterReflection asserts the constant lines, so mirror the class lines as rules do for new nodes
                $lines = [
                    'startLine' => $class->getStartLine(),
                    'endLine' => $class->getStartLine(),
                ];
                array_unshift($class->stmts, new ClassConst([new Const_('EXTRA', new Int_(1), $lines)], 0, $lines));
            }
        }

        $changedNodeScopeRefresher->refresh($class, $sourceFilePath, $scopes[$key]);
    }
};

// warm up autoloading/reflection caches once, excluded from timing
$run();

$memoryStart = memory_get_usage();
$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;
$memory = memory_get_usage() - $memoryStart;

@unlink($sourceFilePath);

$totalCalls = REPEAT * count($classes);

printf('mode: %s%s', $isChanged ? 'changed' : 'same', PHP_EOL);
printf('classes: %d, methods/class: %d, repeats: %d, refresh() calls: %d%s', count($classes), METHODS + 1, REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
printf('memory growth: %.2f MB%s', $memory / 1024 / 1024, PHP_EOL);
