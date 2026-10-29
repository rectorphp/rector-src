<?php

declare(strict_types=1);

/**
 * Benchmark for skipping wrapBinaryOpWithBrackets() on CallLike nodes in BetterStandardPrinter::p().
 *
 * wrapBinaryOpWithBrackets() is a no-op for CallLike nodes, yet it ran its ExprAnalyzer
 * instanceof chain for every printed call. The source is call-heavy and printed fresh
 * (no original tokens), so every node goes through p().
 *
 * Run on both branches to compare:
 *   php bench/better_standard_printer_call_like_bench.php
 *
 * Reports CPU time (user+sys); CPU is the reliable metric, wall is noisy under concurrent load.
 */

use PhpParser\ParserFactory;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\PhpParser\Printer\BetterStandardPrinter;

require __DIR__ . '/../vendor/autoload.php';

const STATEMENTS = 500; // pairs of call-heavy + binary-op statements
const REPEAT = 300;     // prints

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

/** @var BetterStandardPrinter $betterStandardPrinter */
$betterStandardPrinter = $rectorConfig->make(BetterStandardPrinter::class);

$source = "<?php\n\n";
for ($i = 0; $i < STATEMENTS; ++$i) {
    $source .= "\$a{$i} = foo(\$x->bar(baz(\$i, qux(1, 2)), Some::call(\$y)), new Obj(\$z->m()));\n";
    $source .= "\$b{$i} = \$c + \$d * strlen(\$e) && ! \$f;\n";
}

$stmts = (new ParserFactory())->createForNewestSupportedVersion()->parse($source);

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

// warm up autoloading once, excluded from timing; output hash guards against behaviour change
$printed = $betterStandardPrinter->prettyPrintFile($stmts);

$cpuStart = $cpuSeconds();
for ($i = 0; $i < REPEAT; ++$i) {
    $betterStandardPrinter->prettyPrintFile($stmts);
}

$cpu = $cpuSeconds() - $cpuStart;

printf("statements:     %d\n", STATEMENTS * 2);
printf("prints:         %d\n", REPEAT);
printf("CPU total:      %.3f s\n", $cpu);
printf("CPU per print:  %.3f ms\n", $cpu / REPEAT * 1000);
printf("output md5:     %s\n", md5($printed));
