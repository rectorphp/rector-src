<?php

declare(strict_types=1);

/**
 * Benchmark for the "skip print on unchanged file" change in FileProcessor::processFile().
 *
 * Previously every file - including the majority that no rule touches - paid one full
 * printFormatPreserving() pass just to detect "nothing changed". The change breaks out of
 * the convergence loop before that print when the file is still pristine and the pass
 * recorded no RectorWithLineChange.
 *
 * With a bare container (no rules registered) every file is unchanged, so this isolates
 * exactly one skipped print per file as the only difference between the two branches.
 *
 * Run on both branches to compare:
 *   php bench/skip_unchanged_file_print_bench.php
 *
 * Reports CPU time (user+sys); CPU is the reliable metric, wall is noisy under concurrent load.
 */

use Rector\Application\FileProcessor;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\Configuration\ConfigurationFactory;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\ValueObject\Application\File;

require __DIR__ . '/../vendor/autoload.php';

const STATEMENTS = 300; // methods in the source file, sizes the print cost
const REPEAT = 200;     // files processed

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

$fileProcessor = $rectorConfig->make(FileProcessor::class);

/** @var CurrentFileProvider $currentFileProvider */
$currentFileProvider = $rectorConfig->make(CurrentFileProvider::class);

/** @var DynamicSourceLocatorProvider $dynamicSourceLocatorProvider */
$dynamicSourceLocatorProvider = $rectorConfig->make(DynamicSourceLocatorProvider::class);

/** @var ConfigurationFactory $configurationFactory */
$configurationFactory = $rectorConfig->make(ConfigurationFactory::class);

// build a sizable, already-clean file; no rule is registered so it stays unchanged
$source = "<?php\n\ndeclare(strict_types=1);\n\nnamespace Bench;\n\nclass SomeClass\n{\n";
for ($i = 0; $i < STATEMENTS; ++$i) {
    $source .= "    public function method{$i}(int \$value{$i}): int\n    {\n        return \$value{$i} + {$i};\n    }\n\n";
}

$source .= "}\n";

$sourceFilePath = tempnam(sys_get_temp_dir(), 'bench_') . '.php';
file_put_contents($sourceFilePath, $source);

$dynamicSourceLocatorProvider->setFilePath($sourceFilePath);
$configuration = $configurationFactory->createForTests([$sourceFilePath]);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($fileProcessor, $currentFileProvider, $sourceFilePath, $configuration): void {
    // File is single-use (hydrateStmtsAndTokens rejects a second hydrate), so make a fresh one
    $file = new File($sourceFilePath, file_get_contents($sourceFilePath));
    $currentFileProvider->setFile($file);
    $fileProcessor->processFile($file, $configuration);
};

// warm up autoloading/reflection/scope caches once, excluded from timing
$run();

$wallStart = microtime(true);
$cpuStart = $cpuSeconds();

for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}

$cpu = $cpuSeconds() - $cpuStart;
$wall = microtime(true) - $wallStart;

@unlink($sourceFilePath);

printf('statements: %d, files processed: %d%s', STATEMENTS, REPEAT, PHP_EOL);
printf('CPU:  %.4f s  (%.3f ms/file)%s', $cpu, $cpu / REPEAT * 1_000, PHP_EOL);
printf('wall: %.4f s%s', $wall, PHP_EOL);
