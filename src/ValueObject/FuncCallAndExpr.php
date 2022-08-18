<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;

final class FuncCallAndExpr
{
    public function __construct(
        private readonly FuncCall $funcCall,
        private readonly Expr $expr
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
