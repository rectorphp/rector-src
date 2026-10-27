<?php

declare(strict_types=1);

/**
 * Benchmark for RectorRegistry::forNodeAndFile().
 *
 * The traverser asks the registry for the rules of every visited node. forNodeAndFile() then
 * asks the Skipper, per subscribed rule, whether that rule is skipped for the file - and
 * Skipper::matchSkip() walks every configured skip with is_a() plus path matching. The answer
 * only depends on the rule and the file path, so it repeats for every node of the same class
 * in the same file, and again on every fixpoint pass.
 *
 * This bench registers the real CodeQuality rules, configures a few skips the way a real
 * rector.php does, and calls forNodeAndFile() for every node of a large file over several
 * passes, the way the traverser does.
 *
 * Run on both branches to compare:
 *   php bench/rector_registry_for_node_and_file_bench.php
 *
 * Reports CPU time (user+sys) and wall time; CPU is the reliable metric, wall is noisy
 * under concurrent load.
 */

use PhpParser\Node;
use PhpParser\NodeFinder;
use Rector\Application\RectorRegistry;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\Skipper\Skipper\Skipper;
use Rector\Testing\TestingParser\TestingParser;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Configuration;
use Rector\VersionBonding\ComposerPackageConstraintFilter;
use Rector\VersionBonding\PhpVersionedFilter;

require __DIR__ . '/../vendor/autoload.php';

const PASSES = 3;   // fixpoint passes over the same file
const REPEAT = 20;  // outer repeats, each with a fresh file

$rectorConfig = new LazyContainerFactory()->create();
$rectorConfig->boot();

/** @var TestingParser $testingParser */
$testingParser = $rectorConfig->make(TestingParser::class);

$rectors = [];
foreach (glob(__DIR__ . '/../rules/CodeQuality/Rector/*/*.php') ?: [] as $rulePath) {
    $ruleClass = 'Rector\\CodeQuality\\Rector\\' . basename(dirname($rulePath)) . '\\' . basename($rulePath, '.php');
    if (! is_a($ruleClass, RectorInterface::class, true)) {
        continue;
    }

    if (is_a($ruleClass, ConfigurableRectorInterface::class, true)) {
        continue;
    }

    $rectors[] = $rectorConfig->make($ruleClass);
}

// a typical rector.php skip list: a couple of skip-everywhere rules and rule-scoped path skips
SimpleParameterProvider::setParameter(Option::SKIP, [
    $rectors[0]::class,
    $rectors[1]::class => [__DIR__ . '/../src/Foo/*', '*/legacy/*'],
    $rectors[2]::class => ['*/Generated/*'],
    __DIR__ . '/../vendor/*',
    '*/cache/*',
]);

$configurationRuleFilter = $rectorConfig->make(ConfigurationRuleFilter::class);
$configurationRuleFilter->setConfiguration(new Configuration());

$rectorRegistry = new RectorRegistry(
    $rectorConfig->make(PhpVersionedFilter::class),
    $rectorConfig->make(ComposerPackageConstraintFilter::class),
    $configurationRuleFilter,
    $rectorConfig->make(Skipper::class),
    $rectors,
);

$sourceFilePath = __DIR__ . '/../src/NodeTypeResolver/NodeTypeResolver.php';

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

// parse every file up front, so only the registry lookups are timed; each run gets its own File
$parsedFiles = [];
for ($repeat = 0; $repeat <= REPEAT; ++$repeat) {
    $file = $testingParser->parseFilePathToFile($sourceFilePath);

    /** @var Node[] $nodes */
    $nodes = new NodeFinder()->find($file->getNewStmts(), static fn (Node $node): bool => true);
    $parsedFiles[] = [$file, $nodes];
}

$ruleCount = 0;

$run = static function (File $file, array $nodes) use ($rectorRegistry, &$ruleCount): void {
    $ruleCount = 0;

    for ($pass = 0; $pass < PASSES; ++$pass) {
        foreach ($nodes as $node) {
            $ruleCount += count($rectorRegistry->forNodeAndFile($node, $file));
        }
    }
};

$measure = static function () use ($run, $cpuSeconds, $parsedFiles): array {
    // warm up autoloading / registry once, excluded from timing
    $run(...$parsedFiles[REPEAT]);

    $cpuStart = $cpuSeconds();
    $wallStart = hrtime(true);

    for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
        $run(...$parsedFiles[$repeat]);
    }

    return [
        'cpu' => $cpuSeconds() - $cpuStart,
        'wall' => (hrtime(true) - $wallStart) / 1_000_000_000,
    ];
};

$result = $measure();

printf('RectorRegistry::forNodeAndFile benchmark%s', PHP_EOL);
printf(
    '  rules=%d nodes=%d passes=%d repeat=%d rules-returned-per-run=%d%s',
    count($rectors),
    count($parsedFiles[0][1]),
    PASSES,
    REPEAT,
    $ruleCount,
    PHP_EOL
);
printf('%s', PHP_EOL);
printf('  forNodeAndFile  cpu=%.4fs  wall=%.4fs%s', $result['cpu'], $result['wall'], PHP_EOL);
