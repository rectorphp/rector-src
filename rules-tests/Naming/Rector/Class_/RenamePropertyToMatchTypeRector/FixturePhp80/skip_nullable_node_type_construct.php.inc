<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;

final class SkipNullableNodeTypeConstruct
{
    /**
     * @param FuncCall|StaticCall|MethodCall|null $call
     */
    public function __construct(
        private ?Expr $call
    ) {
    }

    /**
     * @return FuncCall|StaticCall|MethodCall|null
     */
    public function getCall(): ?Expr
    {
        return $this->call;
    }
}
