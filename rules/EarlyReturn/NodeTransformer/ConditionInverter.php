<?php

declare(strict_types=1);

namespace Rector\EarlyReturn\NodeTransformer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BooleanNot;
use Rector\NodeManipulator\BinaryOpManipulator;

final class ConditionInverter
{
    public function __construct(
        private readonly BinaryOpManipulator $binaryOpManipulator
    ) {
    }

    public function createInvertedCondition(Expr $expr): Expr
    {
        // inverse condition
        if ($expr instanceof BinaryOp) {
            $binaryOp = $this->binaryOpManipulator->invertCondition($expr);
            if (! $binaryOp instanceof BinaryOp) {
                return new BooleanNot($expr);
            }

            if ($binaryOp instanceof BooleanAnd) {
                return new BooleanNot($expr);
            }

            return $binaryOp;
        }

        if ($expr instanceof BooleanNot) {
            return $expr->expr;
        }

        return new BooleanNot($expr);
    }
}
