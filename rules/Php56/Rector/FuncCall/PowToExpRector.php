<?php

declare(strict_types=1);

namespace Rector\Php56\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\Pow;
use PhpParser\Node\Expr\BitwiseNot;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\ErrorSuppress;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\PowToExpRectorTest
 */
final class PowToExpRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes `pow(val, val2)` to `**` (exp) parameter',
            [new CodeSample('pow(1, 2);', '1**2;')]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'pow')) {
            return null;
        }

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $firstExpr = $node->getArgs()[0]
            ->value;
        $secondExpr = $node->getArgs()[1]
            ->value;

        // ** binds tighter than most operators, so operands with lower precedence must be
        // wrapped in parentheses to keep the original semantics, e.g. pow(~3, 4) => (~3) ** 4
        if ($this->isLowerPrecedenceThanPowLeft($firstExpr)) {
            $firstExpr->setAttribute(AttributeKey::WRAPPED_IN_PARENTHESES, true);
        }

        if ($this->isLowerPrecedenceThanPowRight($secondExpr)) {
            $secondExpr->setAttribute(AttributeKey::WRAPPED_IN_PARENTHESES, true);
        }

        return new Pow($firstExpr, $secondExpr);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::EXP_OPERATOR;
    }

    /**
     * Operators that bind looser than ** on the left-hand side, so pow($operand, $y) would be
     * misparsed without parentheses. Unary operators and casts are legal bare on the right-hand
     * side of ** (2 ** -3), so they only need wrapping when used as the left operand.
     */
    private function isLowerPrecedenceThanPowLeft(Expr $expr): bool
    {
        // a plain Assign as left operand is already parenthesized by BetterStandardPrinter,
        // wrapping it again here would produce a double set of parentheses
        if ($expr instanceof Assign) {
            return false;
        }

        if ($expr instanceof UnaryMinus
            || $expr instanceof UnaryPlus
            || $expr instanceof BitwiseNot
            || $expr instanceof BooleanNot
            || $expr instanceof ErrorSuppress
            || $expr instanceof Cast
            || $expr instanceof Instanceof_) {
            return true;
        }

        return $this->isLowerPrecedenceThanPowRight($expr);
    }

    /**
     * Operators that would otherwise swallow the ** expression on either side, e.g.
     * pow(2, $a ? 3 : 4) must become 2 ** ($a ? 3 : 4), not 2 ** $a ? 3 : 4.
     */
    private function isLowerPrecedenceThanPowRight(Expr $expr): bool
    {
        return $expr instanceof Ternary
            || $expr instanceof Assign
            || $expr instanceof AssignRef
            || $expr instanceof AssignOp
            || $expr instanceof Print_
            || $expr instanceof Yield_
            || $expr instanceof YieldFrom;
    }
}
