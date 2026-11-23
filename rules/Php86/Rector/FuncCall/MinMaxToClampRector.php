<?php

declare(strict_types=1);

namespace Rector\Php86\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\InterpolatedString;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

/**
 * @see \Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\MinMaxToClampRectorTest
 */
final class MinMaxToClampRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert nested min()/max() calls to clamp()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$result = max(0, min(100, $value));
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$result = clamp($value, 0, 100);
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if ($this->isName($node, 'max')) {
            return $this->matchClampFuncCall($node, 'min');
        }

        if ($this->isName($node, 'min')) {
            return $this->matchClampFuncCall($node, 'max');
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_86;
    }

    private function matchClampFuncCall(FuncCall $outerFuncCall, string $expectedInnerFuncName): ?FuncCall
    {
        $args = $outerFuncCall->getArgs();

        if (count($args) !== 2) {
            return null;
        }

        if (! $this->isSupportedArg($args[0]) || ! $this->isSupportedArg($args[1])) {
            return null;
        }

        $leftValue = $args[0]->value;
        $rightValue = $args[1]->value;

        if ($leftValue instanceof FuncCall) {
            return $this->createClampFuncCall($outerFuncCall, $leftValue, $rightValue, $expectedInnerFuncName);
        }

        if ($rightValue instanceof FuncCall) {
            return $this->createClampFuncCall($outerFuncCall, $rightValue, $leftValue, $expectedInnerFuncName);
        }

        return null;
    }

    private function createClampFuncCall(
        FuncCall $outerFuncCall,
        FuncCall $innerFuncCall,
        Expr $outerBoundExpr,
        string $expectedInnerFuncName
    ): ?FuncCall {
        if ($innerFuncCall->isFirstClassCallable()) {
            return null;
        }

        if (! $this->isName($innerFuncCall, $expectedInnerFuncName)) {
            return null;
        }

        $args = $innerFuncCall->getArgs();
        if (count($args) !== 2) {
            return null;
        }

        if (! $this->isSupportedArg($args[0]) || ! $this->isSupportedArg($args[1])) {
            return null;
        }

        $valueAndBound = $this->matchValueAndKnownBound($args[0]->value, $args[1]->value);
        if ($valueAndBound === null) {
            return null;
        }

        [$valueExpr, $innerBoundExpr] = $valueAndBound;

        if ($this->isName($outerFuncCall, 'max')) {
            [$minExpr, $maxExpr] = [$outerBoundExpr, $innerBoundExpr];
        } else {
            [$minExpr, $maxExpr] = [$innerBoundExpr, $outerBoundExpr];
        }

        // clamp() throws ValueError when min > max, while nested min()/max() does not
        if (! $this->areOrderedConstantBounds($minExpr, $maxExpr)) {
            return null;
        }

        return $this->nodeFactory->createFuncCall('clamp', [$valueExpr, $minExpr, $maxExpr]);
    }

    private function areOrderedConstantBounds(Expr $minExpr, Expr $maxExpr): bool
    {
        if (! $this->isKnownBound($minExpr) || ! $this->isKnownBound($maxExpr)) {
            return false;
        }

        $minValues = $this->getNativeType($minExpr)
            ->getConstantScalarValues();
        $maxValues = $this->getNativeType($maxExpr)
            ->getConstantScalarValues();

        // clamp() throws ValueError on a NAN bound too
        foreach ([...$minValues, ...$maxValues] as $value) {
            if (is_float($value) && is_nan($value)) {
                return false;
            }
        }

        // e.g. PHP_INT_MAX differs per platform, so every possible value must be ordered
        foreach ($minValues as $minValue) {
            foreach ($maxValues as $maxValue) {
                if ($minValue > $maxValue) {
                    return false;
                }
            }
        }

        return true;
    }

    private function isSupportedArg(Arg $arg): bool
    {
        return ! $arg->unpack && ! $arg->name instanceof Identifier;
    }

    /**
     * @return array{Expr, Expr}|null
     */
    private function matchValueAndKnownBound(Expr $firstExpr, Expr $secondExpr): ?array
    {
        $isFirstKnownBound = $this->isKnownBound($firstExpr);
        $isSecondKnownBound = $this->isKnownBound($secondExpr);

        if ($isFirstKnownBound === $isSecondKnownBound) {
            return null;
        }

        if ($isFirstKnownBound) {
            return [$secondExpr, $firstExpr];
        }

        return [$firstExpr, $secondExpr];
    }

    private function isKnownBound(Expr $expr): bool
    {
        if (! $this->isConstantExpr($expr)) {
            return false;
        }

        return $this->getNativeType($expr)
            ->isConstantScalarValue()
            ->yes();
    }

    private function isConstantExpr(Expr $expr): bool
    {
        if ($expr instanceof Scalar) {
            return ! $expr instanceof InterpolatedString;
        }

        if ($expr instanceof ConstFetch) {
            return true;
        }

        if ($expr instanceof ClassConstFetch) {
            return $expr->class instanceof Name && ! $this->isName($expr->class, 'static');
        }

        if ($expr instanceof UnaryMinus || $expr instanceof UnaryPlus) {
            return $this->isConstantExpr($expr->expr);
        }

        if ($expr instanceof BinaryOp) {
            return $this->isConstantExpr($expr->left) && $this->isConstantExpr($expr->right);
        }

        return false;
    }
}
