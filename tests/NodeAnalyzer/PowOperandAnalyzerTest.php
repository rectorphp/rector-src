<?php

declare(strict_types=1);

namespace Rector\Tests\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;
use PhpParser\Node\Expr\BitwiseNot;
use PhpParser\Node\Expr\Cast\Int_ as CastInt;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\NodeAnalyzer\PowOperandAnalyzer;

final class PowOperandAnalyzerTest extends TestCase
{
    private PowOperandAnalyzer $powOperandAnalyzer;

    protected function setUp(): void
    {
        $this->powOperandAnalyzer = new PowOperandAnalyzer();
    }

    #[DataProvider('provideLeftOperand')]
    public function testLeftOperand(Expr $expr, bool $expected): void
    {
        $this->assertSame($expected, $this->powOperandAnalyzer->isLowerPrecedenceAsLeftOperand($expr));
    }

    #[DataProvider('provideRightOperand')]
    public function testRightOperand(Expr $expr, bool $expected): void
    {
        $this->assertSame($expected, $this->powOperandAnalyzer->isLowerPrecedenceAsRightOperand($expr));
    }

    /**
     * @return iterable<array{Expr, bool}>
     */
    public static function provideLeftOperand(): iterable
    {
        yield [new BitwiseNot(new Int_(3)), true];
        yield [new UnaryMinus(new Int_(3)), true];
        yield [new CastInt(new Variable('a')), true];
        yield [new Instanceof_(new Variable('a'), new Name('DateTime')), true];
        yield [new Ternary(new Variable('a'), new Int_(1), new Int_(2)), true];
        yield [new AssignPlus(new Variable('a'), new Int_(4)), true];

        // a plain Assign is already parenthesized by the printer on the left side
        yield [new Assign(new Variable('a'), new Int_(4)), false];
        yield [new Variable('a'), false];
        yield [new Int_(3), false];
    }

    /**
     * @return iterable<array{Expr, bool}>
     */
    public static function provideRightOperand(): iterable
    {
        yield [new Ternary(new Variable('a'), new Int_(1), new Int_(2)), true];
        yield [new Assign(new Variable('a'), new Int_(4)), true];
        yield [new AssignPlus(new Variable('a'), new Int_(4)), true];

        // unary operators and casts are legal bare on the right side of **
        yield [new BitwiseNot(new Int_(3)), false];
        yield [new UnaryMinus(new Int_(3)), false];
        yield [new CastInt(new Variable('a')), false];
        yield [new Variable('a'), false];
    }
}
