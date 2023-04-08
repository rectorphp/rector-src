<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use Rector\Core\Validation\RectorAssert;
use Rector\Transform\Contract\ValueObject\ArgumentFuncCallToMethodCallInterface;

final class ArrayFuncCallToMethodCall implements ArgumentFuncCallToMethodCallInterface
{
    public function __construct(
        private readonly string $function,
        private readonly string $class,
        private readonly string $arrayMethod,
        private readonly string $nonArrayMethod
    ) {
        RectorAssert::className($class);
        RectorAssert::functionName($function);
        RectorAssert::methodName($arrayMethod);
        RectorAssert::methodName($nonArrayMethod);
    }

    public function getFunction(): string
    {
        return $this->function;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getArrayMethod(): string
    {
        return $this->arrayMethod;
    }

    public function getNonArrayMethod(): string
    {
        return $this->nonArrayMethod;
    }
}
