<?php

declare(strict_types=1);

/**
 * Benchmark + equivalence check for the expr-bearing node guard in PHPStanNodeScopeResolver.
 *
 * The resolver propagates the scope to $node->expr for a hand-listed set of node types via a long
 * instanceof chain. This compares that chain against the shorter property_exists($node, 'expr').
 *
 * Two things are measured:
 *   1. Equivalence - which node types each predicate matches. They are NOT the same set.
 *   2. Speed - CPU time (user+sys) over a REPEAT loop; wall is reported but noisy under load.
 *
 * Run:
 *   php bench/scope_expr_dispatch_bench.php
 */

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BitwiseNot;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\ErrorSuppress;
use PhpParser\Node\Expr\Eval_;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;

require __DIR__ . '/../vendor/autoload.php';

const REPEAT = 500_000;

/**
 * Current production guard: the explicit instanceof chain.
 */
function matchesChain(Node $node): bool
{
    return (
        $node instanceof Expression ||
        $node instanceof Return_ ||
        $node instanceof EnumCase ||
        $node instanceof Cast ||
        $node instanceof YieldFrom ||
        $node instanceof UnaryMinus ||
        $node instanceof UnaryPlus ||
        $node instanceof Throw_ ||
        $node instanceof Empty_ ||
        $node instanceof BooleanNot ||
        $node instanceof Clone_ ||
        $node instanceof ErrorSuppress ||
        $node instanceof BitwiseNot ||
        $node instanceof Eval_ ||
        $node instanceof Print_ ||
        $node instanceof Exit_ ||
        $node instanceof Include_ ||
        $node instanceof Instanceof_
    ) && $node->expr instanceof Expr;
}

/**
 * Candidate guard: a single property lookup.
 */
function matchesPropertyExists(Node $node): bool
{
    return property_exists($node, 'expr') && $node->expr instanceof Expr;
}

$v = new Variable('x');

// nodes that carry an $expr property (set it so the Expr guard passes)
$withExpr = [
    new Expression($v),
    new Return_($v),
    new EnumCase('CASE', $v),
    new Cast\Int_($v),
    new YieldFrom($v),
    new UnaryMinus($v),
    new UnaryPlus($v),
    new Throw_($v),
    new Empty_($v),
    new BooleanNot($v),
    new Clone_($v),
    new ErrorSuppress($v),
    new BitwiseNot($v),
    new Eval_($v),
    new Print_($v),
    new Exit_($v),
    new Include_($v, Include_::TYPE_INCLUDE),
    new Instanceof_($v, new Node\Name('Foo')),
    // the five extra types property_exists also matches, the chain does not:
    new Assign($v, $v),
    new AssignOp\Plus($v, $v),
    new AssignRef($v, $v),
    new ArrowFunction([
        'expr' => $v,
    ]),
    new Foreach_($v, $v),
];

// nodes without an $expr property (both predicates must reject)
$withoutExpr = [
    new Variable('y'),
    new Nop(),
    new Node\Name('Bar'),
    new Node\Arg($v),
];

$all = [...$withExpr, ...$withoutExpr];

// --- 1. equivalence ----------------------------------------------------------
$divergent = [];
foreach ($all as $node) {
    if (matchesChain($node) !== matchesPropertyExists($node)) {
        $divergent[] = $node::class;
    }
}

echo "Equivalence check\n";
echo "-----------------\n";
if ($divergent === []) {
    echo "identical on sample set\n\n";
} else {
    echo "DIVERGENT - property_exists matches but chain does not:\n";
    foreach ($divergent as $class) {
        echo '  - ' . $class . "\n";
    }

    echo "\n";
}

// --- 2. speed ----------------------------------------------------------------
function cpuSeconds(): float
{
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1e6
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1e6;
}

function run(callable $predicate, array $all): void
{
    for ($r = 0; $r < REPEAT; ++$r) {
        foreach ($all as $node) {
            $predicate($node);
        }
    }
}

// warmup (excluded)
run(matchesChain(...), $all);
run(matchesPropertyExists(...), $all);

$cpu0 = cpuSeconds();
$wall0 = microtime(true);
run(matchesChain(...), $all);
$chainCpu = cpuSeconds() - $cpu0;
$chainWall = microtime(true) - $wall0;

$cpu0 = cpuSeconds();
$wall0 = microtime(true);
run(matchesPropertyExists(...), $all);
$peCpu = cpuSeconds() - $cpu0;
$peWall = microtime(true) - $wall0;

$calls = REPEAT * count($all);

printf("Speed (%s calls each)\n", number_format($calls));
echo "---------------------\n";
printf("instanceof chain   : CPU %.3fs  wall %.3fs  (%.1f ns/call)\n", $chainCpu, $chainWall, $chainCpu / $calls * 1e9);
printf("property_exists    : CPU %.3fs  wall %.3fs  (%.1f ns/call)\n", $peCpu, $peWall, $peCpu / $calls * 1e9);
printf("ratio (chain/pe)   : %.2fx CPU\n", $peCpu > 0 ? $chainCpu / $peCpu : 0.0);
