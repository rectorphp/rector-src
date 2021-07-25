<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\LNumber;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\NodeNameResolver\NodeNameResolver;

final class CountManipulator
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeComparator $nodeComparator
    ) {
    }

    public function isCounterHigherThanOne(Node $node, Expr $expr): bool
    {
        // e.g. count($values) > 0
        if ($node instanceof Greater) {
            return $this->isGreater($node, $expr);
        }

        // e.g. count($values) >= 1
        if ($node instanceof GreaterOrEqual) {
            return $this->isGreaterOrEqual($node, $expr);
        }

        // e.g. 0 < count($values)
        if ($node instanceof Smaller) {
            return $this->isSmaller($node, $expr);
        }

        // e.g. 1 <= count($values)
        if ($node instanceof SmallerOrEqual) {
            return $this->isSmallerOrEqual($node, $expr);
        }

        return false;
    }

    private function isGreater(Greater $greater, Expr $expr): bool
    {
        if (! $this->isNumber($greater->right, 0)) {
            return false;
        }

        return $this->isCountWithExpression($greater->left, $expr);
    }

    private function isGreaterOrEqual(GreaterOrEqual $greaterOrEqual, Expr $expr): bool
    {
        if (! $this->isNumber($greaterOrEqual->right, 1)) {
            return false;
        }

        return $this->isCountWithExpression($greaterOrEqual->left, $expr);
    }

    private function isSmaller(Smaller $smaller, Expr $expr): bool
    {
        if (! $this->isNumber($smaller->left, 0)) {
            return false;
        }

        return $this->isCountWithExpression($smaller->right, $expr);
    }

    private function isSmallerOrEqual(SmallerOrEqual $smallerOrEqual, Expr $expr): bool
    {
        if (! $this->isNumber($smallerOrEqual->left, 1)) {
            return false;
        }

        return $this->isCountWithExpression($smallerOrEqual->right, $expr);
    }

    private function isNumber(Node $node, int $value): bool
    {
        if (! $node instanceof LNumber) {
            return false;
        }

        return $node->value === $value;
    }

    private function isCountWithExpression(Node $node, Expr $expr): bool
    {
        if (! $node instanceof FuncCall) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node, 'count')) {
            return false;
        }

        $countedExpr = $node->args[0]->value;

        return $this->nodeComparator->areNodesEqual($countedExpr, $expr);
    }
}
