<?php

declare(strict_types=1);

use PhpParser\Node\Stmt\Class_;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\TestingParser\TestingParser;

require __DIR__ . '/../vendor/autoload.php';

const CLASSES = 150;
const REPEAT = 100;

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

/** @var BetterNodeFinder $betterNodeFinder */
$betterNodeFinder = $rectorConfig->make(BetterNodeFinder::class);

$rector = $rectorConfig->make(RemoveUnusedPrivateMethodRector::class);

$source = "<?php\n\nnamespace Bench;\n\nuse PHPUnit\\Framework\\Attributes\\DataProvider;\nuse PHPUnit\\Framework\\TestCase;\n\n";
for ($i = 0; $i < CLASSES; ++$i) {
    $source .= "final class Service{$i} extends TestCase\n{\n";
    $source .= "    #[DataProvider('provideValues')]\n";
    $source .= "    public function testValue(int \$value): void\n    {\n";
    $source .= "        self::assertSame(\$value, self::class::expectedValue());\n";
    $source .= "        \$this->locallyUsed();\n";
    $source .= "    }\n\n";
    $source .= "    private static function provideValues(): array\n    {\n        return [[42]];\n    }\n\n";
    $source .= "    private static function expectedValue(): int\n    {\n        return 42;\n    }\n\n";
    $source .= "    private function locallyUsed(): void\n    {\n    }\n";
    $source .= "}\n\n";
}

$tempBase = tempnam(sys_get_temp_dir(), 'bench_');
$sourceFilePath = $tempBase . '.php';
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

$run();

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

if (is_file($sourceFilePath)) {
    unlink($sourceFilePath);
}

if (is_file($tempBase)) {
    unlink($tempBase);
}

$totalCalls = REPEAT * count($classes);

printf('classes: %d, repeats: %d, refactor() calls: %d%s', count($classes), REPEAT, $totalCalls, PHP_EOL);
printf('CPU:  %.4f s  (%.3f us/call)%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
