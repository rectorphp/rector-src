<?php

declare(strict_types=1);

namespace Rector\NodeCollector\ValueObject;

use PhpParser\Node\Expr;

final class ArrayCallableDynamicMethod
{
    /**
     * @param mixed $method
     */
    public function __construct(
        private Expr $callerExpr,
        private string $class,
        private $method
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    public function getCallerExpr(): Expr
    {
        return $this->callerExpr;
    }
}
