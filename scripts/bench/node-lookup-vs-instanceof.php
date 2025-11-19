<?php

declare(strict_types=1);

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignOp\BitwiseAnd;
use PhpParser\Node\Expr\AssignOp\BitwiseOr;
use PhpParser\Node\Expr\AssignOp\BitwiseXor;
use PhpParser\Node\Expr\AssignOp\Coalesce;
use PhpParser\Node\Expr\AssignOp\Concat;
use PhpParser\Node\Expr\AssignOp\Div;
use PhpParser\Node\Expr\AssignOp\Minus;
use PhpParser\Node\Expr\AssignOp\Mod;
use PhpParser\Node\Expr\AssignOp\Mul;
use PhpParser\Node\Expr\AssignOp\Plus;
use PhpParser\Node\Expr\AssignOp\Pow;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;
use PhpParser\Node\Expr\AssignOp\ShiftRight;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\BinaryOp\Spaceship;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\ErrorSuppress;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\ShellExec;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\MatchArm;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Scalar\MagicConst\Class_;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Scalar\MagicConst\File;
use PhpParser\Node\Scalar\MagicConst\Function_;
use PhpParser\Node\Scalar\MagicConst\Line;
use PhpParser\Node\Scalar\MagicConst\Method;
use PhpParser\Node\Scalar\MagicConst\Namespace_;
use PhpParser\Node\Scalar\MagicConst\Trait_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Global_;
use PhpParser\Node\Stmt\Goto_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\HaltCompiler;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Label;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyHook;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Static_;
use PhpParser\Node\Stmt\StaticVar;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\UnionType;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

require __DIR__ . '/../../vendor/autoload.php';

final class Map
{
    public const NODE_INSTANCE_TO_TYPE_MAP = [
        Arg::class => [Arg::class],
        ArrayItem::class => [ArrayItem::class],
        ComplexType::class => [IntersectionType::class, UnionType::class],
        Expr::class => [
            ArrayDimFetch::class,
            Array_::class,
            \PhpParser\Node\Expr\ArrayItem::class,
            Assign::class,
            AssignOp::class,
            AssignRef::class,
            BinaryOp::class,
            BooleanNot::class,
            Cast::class,
            //\PhpParser\Node\Expr\Clone_:class,
            Closure::class,
            ClosureUse::class,
            ConstFetch::class,
            //\PhpParser\Node\Expr\Empty_:class,
            ErrorSuppress::class,
            Exit_::class,
            FuncCall::class,
            //\PhpParser\Node\Expr\Include_:class,
            Instanceof_::class,
            //\PhpParser\Node\Expr\Isset_:class,
            List_::class,
            //\PhpParser\Node\Expr\MathError::class,
            Match_::class,
            MethodCall::class,
            New_::class,
            NullsafeMethodCall::class,
            NullsafePropertyFetch::class,
            PostDec::class,
            PostInc::class,
            PreDec::class,
            PreInc::class,
            Print_::class,
            PropertyFetch::class,
            ShellExec::class,
            StaticCall::class,
            StaticPropertyFetch::class,
            Ternary::class,
            Throw_::class,
            UnaryMinus::class,
            UnaryPlus::class,
            Variable::class,
            Yield_::class,
            YieldFrom::class,
            ArrowFunction::class,
        ],
        AssignOp::class => [
            BitwiseAnd::class,
            BitwiseOr::class,
            BitwiseXor::class,
            Coalesce::class,
            Concat::class,
            Div::class,
            Minus::class,
            Mod::class,
            Mul::class,
            Plus::class,
            Pow::class,
            ShiftLeft::class,
            ShiftRight::class,
        ],
        BinaryOp::class => [
            \PhpParser\Node\Expr\BinaryOp\BitwiseAnd::class,
            \PhpParser\Node\Expr\BinaryOp\BitwiseOr::class,
            \PhpParser\Node\Expr\BinaryOp\BitwiseXor::class,
            BooleanAnd::class,
            BooleanOr::class,
            \PhpParser\Node\Expr\BinaryOp\Coalesce::class,
            \PhpParser\Node\Expr\BinaryOp\Concat::class,
            \PhpParser\Node\Expr\BinaryOp\Div::class,
            Equal::class,
            Greater::class,
            GreaterOrEqual::class,
            Identical::class,
            LogicalAnd::class,
            LogicalOr::class,
            LogicalXor::class,
            \PhpParser\Node\Expr\BinaryOp\Minus::class,
            \PhpParser\Node\Expr\BinaryOp\Mod::class,
            \PhpParser\Node\Expr\BinaryOp\Mul::class,
            NotEqual::class,
            NotIdentical::class,
            \PhpParser\Node\Expr\BinaryOp\Plus::class,
            \PhpParser\Node\Expr\BinaryOp\Pow::class,
            \PhpParser\Node\Expr\BinaryOp\ShiftLeft::class,
            \PhpParser\Node\Expr\BinaryOp\ShiftRight::class,
            Smaller::class,
            SmallerOrEqual::class,
            Spaceship::class,
        ],
        MatchArm::class => [MatchArm::class],
        Name::class => [Name::class, FullyQualified::class, Relative::class],
        Param::class => [Param::class],
        Scalar::class => [
            DNumber::class,
            Encapsed::class,
            EncapsedStringPart::class,
            LNumber::class,
            MagicConst::class,
            String_::class,
        ],
        MagicConst::class => [
            Class_::class,
            Dir::class,
            File::class,
            Function_::class,
            Line::class,
            Method::class,
            Namespace_::class,
            Trait_::class,
        ],
        Stmt::class => [
            Break_::class,
            Case_::class,
            Catch_::class,
            \PhpParser\Node\Stmt\Class_::class,
            ClassConst::class,
            ClassMethod::class,
            Const_::class,
            Continue_::class,
            Declare_::class,
            Do_::class,
            Echo_::class,
            Enum_::class,
            Expression::class,
            Finally_::class,
            For_::class,
            Foreach_::class,
            \PhpParser\Node\Stmt\Function_::class,
            Global_::class,
            Goto_::class,
            GroupUse::class,
            HaltCompiler::class,
            If_::class,
            InlineHTML::class,
            Interface_::class,
            Label::class,
            \PhpParser\Node\Stmt\Namespace_::class,
            Property::class,
            PropertyHook::class,
            Return_::class,
            Static_::class,
            StaticVar::class,
            Switch_::class,
            \PhpParser\Node\Stmt\Throw_::class,
            \PhpParser\Node\Stmt\Trait_::class,
            TraitUse::class,
            TryCatch::class,
            Unset_::class,
            Use_::class,
            UseUse::class,
            While_::class,
        ],
        ClassLike::class => [
            \PhpParser\Node\Stmt\Class_::class,
            Interface_::class,
            \PhpParser\Node\Stmt\Trait_::class,
            Enum_::class,
        ],
        FunctionLike::class => [
            ClassMethod::class,
            \PhpParser\Node\Stmt\Function_::class,
            Closure::class,
            ArrowFunction::class,
            \PhpParser\Node\PropertyHook::class,
        ],
    ];
}

$iterations = 10000; // number of traversals per run (set lower if file is big)
$runs = 10;

// -----------------------------------------------------------------------------
// 1. Helpers
// -----------------------------------------------------------------------------

/**
 * Static table version – generic => list of concrete nodes
 */
function isFunctionLikeTable(Node $node): bool
{
    return in_array($node::class, Map::NODE_INSTANCE_TO_TYPE_MAP[FunctionLike::class] ?? [], true);
}

function average(array $values): float
{
    return array_sum($values) / count($values);
}

// global sink to avoid dead-code elimination
$GLOBALS['__bench_sink'] = false;

// -----------------------------------------------------------------------------
// 2. Prepare nodes
// -----------------------------------------------------------------------------

// real nodes from a real file
$phpParserFactory = new ParserFactory();
$phpParser = $phpParserFactory->createForHostVersion();

$stmts = $phpParser->parse(file_get_contents(__DIR__ . '/fixture/SomeSnippet.php'));

$nodeTraverser = new NodeTraverser();

final class CheckPerformanceNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var callable(Node): void
     */
    public $tester;

    public function enterNode(Node $node): void
    {
        // test here
        ($this->tester)($node);
    }
}

$visitor = new CheckPerformanceNodeVisitor();
$nodeTraverser->addVisitor($visitor);

// -----------------------------------------------------------------------------
// 3. Benchmark helper
// -----------------------------------------------------------------------------

/**
 * @param callable(Node): void $callback
 */
function run_benchmark(
    NodeTraverser $nodeTraverser,
    CheckPerformanceNodeVisitor $checkPerformanceNodeVisitor,
    callable $callback,
    array $stmts,
    int $iterations
): float {
    $checkPerformanceNodeVisitor->tester = $callback;

    $start = hrtime(true);

    for ($i = 0; $i < $iterations; ++$i) {
        $nodeTraverser->traverse($stmts);
    }

    $elapsed = hrtime(true) - $start;

    // return ns per traversal
    return $elapsed / $iterations;
}

// -----------------------------------------------------------------------------
// 4. Benchmark with multiple runs
// -----------------------------------------------------------------------------

$tableDurations = [];
$isADurations = [];

for ($run = 0; $run < $runs; ++$run) {
    // Static table
    $tableDurations[] = run_benchmark(
        $nodeTraverser,
        $visitor,
        static function (Node $node): void {
            // static table lookup
            $GLOBALS['__bench_sink'] ^= isFunctionLikeTable($node);
        },
        $stmts,
        $iterations
    );

    // is_a() with true
    $isADurations[] = run_benchmark(
        $nodeTraverser,
        $visitor,
        static function (Node $node): void {
            $GLOBALS['__bench_sink'] ^= $node instanceof FunctionLike;
        },
        $stmts,
        $iterations
    );
}

// -----------------------------------------------------------------------------
// 5. Output
// -----------------------------------------------------------------------------

echo sprintf('Traversals per run: %d%s', $iterations, PHP_EOL);
echo "Runs: {$runs}\n\n";

echo "Average time per traversed file (nanoseconds):\n";
echo 'Static table:           ' . average($tableDurations) . " ns\n";
echo 'is_a() check:           ' . average($isADurations) . " ns\n";

if (average($isADurations) > 0) {
    echo "\nRatio (table / is_a): " . (average($tableDurations) / average($isADurations)) . "\n";
}

echo "\nIgnore sink: " . (int) $GLOBALS['__bench_sink'] . "\n";
