<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Core\NodeAnalyzer\ExprAnalyzer;

final class ArrayAnalyzer
{
    public function __construct(
        private ExprAnalyzer $exprAnalyzer
    ) {
    }

    public function isDynamicArray(Array_ $array): bool
    {
        foreach ($array->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (! $this->isAllowedArrayKey($item->key)) {
                return true;
            }

            if (! $this->isAllowedArrayValue($item->value)) {
                return true;
            }
        }

        return false;
    }

    private function isAllowedArrayKey(?Expr $expr): bool
    {
        if (! $expr instanceof Expr) {
            return true;
        }

        if ($expr instanceof String_) {
            return true;
        }

        return $expr instanceof LNumber;
    }

    private function isAllowedArrayValue(Expr $expr): bool
    {
        if ($expr instanceof Array_) {
            return ! $this->isDynamicArray($expr);
        }

        return ! $this->exprAnalyzer->isDynamicExpr($expr);
    }
}
