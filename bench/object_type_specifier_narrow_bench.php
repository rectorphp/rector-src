<?php

declare(strict_types=1);

/**
 * Benchmark for ObjectTypeSpecifier::narrowToFullyQualifiedOrAliasedObjectType().
 *
 * The method is hit once per object-typed phpdoc/name node during type resolution.
 * The simplification keeps behavior identical; this bench confirms it does not
 * regress CPU. Three representative inputs are looped, each hitting a different
 * exit path:
 *
 *   - preslash:      leading "\\" short-circuit (top of method)
 *   - existing:      resolve() uses + reflectionProvider->hasClass() hit -> FullyQualified
 *   - non-existing:  full fall-through to resolveNamespacedNonExistingObjectType()
 *
 * Run on both branches to compare:
 *   php bench/object_type_specifier_narrow_bench.php
 *
 * Reports CPU time (user+sys); wall is noise under concurrent load.
 */

use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\TypeDeclaration\PHPStan\ObjectTypeSpecifier;

require __DIR__ . '/../vendor/autoload.php';

const NODES = 2000;   // narrow() calls per pass
const REPEAT = 40;    // outer repeats

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var ObjectTypeSpecifier $objectTypeSpecifier */
$objectTypeSpecifier = $rectorConfig->make(ObjectTypeSpecifier::class);

// node only used in the scope/template branch; scope is null here, so a bare dummy is fine
$node = new Identifier('x');

$inputs = [
    new ObjectType('\\' . ObjectType::class), // preslash short-circuit
    new ObjectType(ObjectType::class),        // existing class -> hasClass hit
    new ObjectType('No\\Such\\ClassName'),    // non-existing -> full fall-through
];

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

$run = static function () use ($objectTypeSpecifier, $node, $inputs): void {
    for ($i = 0; $i < NODES; ++$i) {
        foreach ($inputs as $objectType) {
            $objectTypeSpecifier->narrowToFullyQualifiedOrAliasedObjectType($node, $objectType, null);
        }
    }
};

// warm up autoloading once, excluded from timing
$run();

$cpuStart = $cpuSeconds();
for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
    $run();
}
$cpu = $cpuSeconds() - $cpuStart;

$totalCalls = NODES * count($inputs) * REPEAT;

printf('ObjectTypeSpecifier::narrowToFullyQualifiedOrAliasedObjectType benchmark%s', PHP_EOL);
printf('  nodes=%d inputs=%d repeat=%d  (%d calls)%s', NODES, count($inputs), REPEAT, $totalCalls, PHP_EOL);
printf('%s', PHP_EOL);
printf('  cpu=%.4fs  per-call=%.4fus%s', $cpu, $cpu / $totalCalls * 1_000_000, PHP_EOL);
