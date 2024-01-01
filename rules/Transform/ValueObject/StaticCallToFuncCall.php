<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final class StaticCallToFuncCall
{
    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private readonly string $function
    ) {
        RectorAssert::className($class);
        RectorAssert::methodName($method);

        RectorAssert::functionName($function);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getFunction(): string
    {
        return $this->function;
    }
}
