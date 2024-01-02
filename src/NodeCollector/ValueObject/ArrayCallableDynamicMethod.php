<?php

declare(strict_types=1);

namespace Rector\NodeCollector\ValueObject;

use PhpParser\Node\Expr;
use Rector\Validation\RectorAssert;

/**
 * @api
 */
final readonly class ArrayCallableDynamicMethod
{
    public function __construct(
        private Expr $callerExpr,
        private string $class,
        private Expr $method
    ) {
        RectorAssert::className($class);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): Expr
    {
        return $this->method;
    }

    public function getCallerExpr(): Expr
    {
        return $this->callerExpr;
    }
}
