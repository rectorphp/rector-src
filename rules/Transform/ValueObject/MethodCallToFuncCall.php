<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

final class MethodCallToFuncCall
{
    public function __construct(
        private readonly string $objectType,
        private readonly string $methodName,
        private readonly string $functionName
    ) {
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getFunctionName(): string
    {
        return $this->functionName;
    }
}
