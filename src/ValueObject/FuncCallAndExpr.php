<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;

final readonly class FuncCallAndExpr
{
    public function __construct(
        private FuncCall $funcCall,
        private Expr $expr
    ) {
    }

    public function getFuncCall(): FuncCall
    {
        return $this->funcCall;
    }

    public function getExpr(): Expr
    {
        return $this->expr;
    }
}
