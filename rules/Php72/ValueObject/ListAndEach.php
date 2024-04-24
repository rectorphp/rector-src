<?php

declare(strict_types=1);

namespace Rector\Php72\ValueObject;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\List_;

final readonly class ListAndEach
{
    public function __construct(
        private List_ $list,
        private FuncCall $eachFuncCall,
    ) {
    }

    public function getList(): List_
    {
        return $this->list;
    }

    public function getEachFuncCall(): FuncCall
    {
        return $this->eachFuncCall;
    }
}
