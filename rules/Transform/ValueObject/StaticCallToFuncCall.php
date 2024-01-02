<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class StaticCallToFuncCall
{
    public function __construct(
        private string $class,
        private string $method,
        private string $function
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
