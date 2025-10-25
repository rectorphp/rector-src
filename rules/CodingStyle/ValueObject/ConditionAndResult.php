<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use Webmozart\Assert\Assert;

final readonly class ConditionAndResult
{
    public function __construct(
        private Expr $conditionExpr,
        private Expr $resultExpr
    ) {
    }

    public function getConditionExpr(): Expr
    {
        return $this->conditionExpr;
    }

    public function isIdenticalCompare(): bool
    {
        return $this->conditionExpr instanceof Identical;
    }

    public function getIdenticalVariableName(): ?string
    {
        $identical = $this->getConditionIdentical();
        if (! $identical->left instanceof Expr\Variable) {
            return null;
        }

        $variable = $identical->left;
        if ($variable->name instanceof Expr) {
            return null;
        }

        return $variable->name;
    }

    public function getResultExpr(): Expr
    {
        return $this->resultExpr;
    }

    public function getIdenticalExpr(): Expr
    {
        /** @var Identical $identical */
        $identical = $this->conditionExpr;

        return $identical->right;
    }

    private function getConditionIdentical(): Identical
    {
        Assert::isInstanceOf($this->conditionExpr, Identical::class);

        return $this->conditionExpr;
    }
}
