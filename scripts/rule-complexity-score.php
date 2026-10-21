<?php

declare(strict_types=1);

/**
 * Rule complexity score.
 *
 * Ranks Rector rules by how much code they need to run. For each rule it measures:
 *   - deps        - number of constructor-injected service dependencies (direct)
 *   - own LOC     - lines of the rule file itself
 *   - deps LOC    - lines of every class reachable transitively through those deps
 *
 * score = own LOC + deps * DEP_WEIGHT + deps LOC
 *
 * A high score means the rule pulls in a lot of code to do its job. Many test fixtures
 * is a separate risk signal: the more fixtures, the more edge cases it has had to cover,
 * so the more likely it is still unreliable.
 *
 * Usage:
 *   php scripts/rule-complexity-score.php
 *   php scripts/rule-complexity-score.php --top=50 --dep-weight=50
 *
 * Options:
 *   --top=N          rows to print (default 40; use --top=0 for all)
 *   --dep-weight=N   weight per direct dependency in the score (default 50)
 */

use Rector\Scripts\Finder\RectorClassFinder;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

require __DIR__ . '/../vendor/autoload.php';

$options = getopt('', ['top::', 'dep-weight::']);
$top = isset($options['top']) ? (int) $options['top'] : 40;
$depWeight = isset($options['dep-weight']) ? (int) $options['dep-weight'] : 50;

$symfonyStyle = new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());

// 1. collect rule classes from this repo and the sibling packages
$rectorClassFinder = new RectorClassFinder();
$rectorClasses = $rectorClassFinder->find([
    __DIR__ . '/../rules',
    __DIR__ . '/../vendor/rector/rector-doctrine',
    __DIR__ . '/../vendor/rector/rector-phpunit',
    __DIR__ . '/../vendor/rector/rector-symfony',
    __DIR__ . '/../vendor/rector/rector-downgrade-php',
]);

$symfonyStyle->writeln(sprintf('<fg=green>Found %d Rector rules</>', count($rectorClasses)));

// 2. map rule short name to its fixture count (only rector-src ships tests)
$fixtureCountByRule = resolveFixtureCounts(__DIR__ . '/../rules-tests');

// 3. score every rule
$rows = [];

foreach ($rectorClasses as $rectorClass) {
    $reflectionClass = new ReflectionClass($rectorClass);

    $ownLines = countClassLines($reflectionClass);

    $directDeps = resolveDependencyClasses($reflectionClass);
    $depsCount = count($directDeps);

    $reachable = [];
    foreach ($directDeps as $dependencyClass) {
        $reachable = resolveReachable($dependencyClass, $reachable);
    }

    $depsLines = 0;
    foreach (array_keys($reachable) as $reachableClass) {
        $depsLines += countClassLines(new ReflectionClass($reachableClass));
    }

    $score = $ownLines + $depsCount * $depWeight + $depsLines;

    $shortName = $reflectionClass->getShortName();

    $rows[] = [
        'short' => $shortName,
        'deps' => $depsCount,
        'own' => $ownLines,
        'deps_loc' => $depsLines,
        'score' => $score,
        'fixtures' => $fixtureCountByRule[$shortName] ?? 0,
    ];
}

// 4. sort by score, highest first
usort($rows, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

if ($top > 0) {
    $rows = array_slice($rows, 0, $top);
}

$tableRows = array_map(
    static fn (array $row): array => [
        $row['short'],
        $row['deps'],
        $row['own'],
        $row['deps_loc'],
        $row['score'],
        $row['fixtures'],
    ],
    $rows
);

$symfonyStyle->newLine();
$symfonyStyle->table(
    ['Rule', 'Deps', 'Own LOC', 'Deps LOC', 'Score', 'Fixtures'],
    $tableRows
);

/**
 * Lines of the class file, cached per file across calls.
 *
 * @param ReflectionClass<object> $reflectionClass
 */
function countClassLines(ReflectionClass $reflectionClass): int
{
    static $fileLineCache = [];

    $fileName = $reflectionClass->getFileName();
    if (! is_string($fileName)) {
        return 0;
    }

    if (isset($fileLineCache[$fileName])) {
        return $fileLineCache[$fileName];
    }

    $lines = file($fileName);
    $lineCount = $lines === false ? 0 : count($lines);
    $fileLineCache[$fileName] = $lineCount;

    return $lineCount;
}

/**
 * Class-string of every non-builtin constructor dependency.
 *
 * @param ReflectionClass<object> $reflectionClass
 * @return class-string[]
 */
function resolveDependencyClasses(ReflectionClass $reflectionClass): array
{
    $constructorReflection = $reflectionClass->getConstructor();
    if (! $constructorReflection instanceof ReflectionMethod) {
        return [];
    }

    $dependencyClasses = [];
    foreach ($constructorReflection->getParameters() as $parameterReflection) {
        $type = $parameterReflection->getType();
        if (! $type instanceof ReflectionNamedType) {
            continue;
        }

        if ($type->isBuiltin()) {
            continue;
        }

        $typeName = $type->getName();

        // self/parent/static point back at the class itself, not an injected service
        if (in_array($typeName, ['self', 'parent', 'static'], true)) {
            continue;
        }

        /** @var class-string $typeName */
        $dependencyClasses[] = $typeName;
    }

    return $dependencyClasses;
}

/**
 * Walk the constructor graph, collecting every reachable class once.
 *
 * @param class-string $class
 * @param array<class-string, true> $reachable
 * @return array<class-string, true>
 */
function resolveReachable(string $class, array $reachable): array
{
    if (isset($reachable[$class])) {
        return $reachable;
    }

    $reachable[$class] = true;

    foreach (resolveDependencyClasses(new ReflectionClass($class)) as $dependencyClass) {
        $reachable = resolveReachable($dependencyClass, $reachable);
    }

    return $reachable;
}

/**
 * Count *.php.inc fixtures grouped by the rule directory that holds them.
 *
 * @return array<string, int>
 */
function resolveFixtureCounts(string $testsDirectory): array
{
    if (! is_dir($testsDirectory)) {
        return [];
    }

    $ruleTestDirectories = (new Finder())
        ->in($testsDirectory)
        ->directories()
        ->name('#Rector$#')
        ->getIterator();

    $fixtureCountByRule = [];
    foreach ($ruleTestDirectories as $ruleTestDirectory) {
        $basename = $ruleTestDirectory->getBasename();
        if ($basename === 'Rector') {
            continue;
        }

        $fixtureCountByRule[$basename] = (new Finder())
            ->files()
            ->name('*.php.inc')
            ->in($ruleTestDirectory->getPathname())
            ->count();
    }

    return $fixtureCountByRule;
}
