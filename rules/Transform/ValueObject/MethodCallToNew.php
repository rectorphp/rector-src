<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;

final readonly class MethodCallToNew
{
    /**
     * @param class-string $newClassString
     */
    public function __construct(
        private ObjectType $objectType,
        private string $methodName,
        private string $newClassString
    ) {
    }

    public function getObject(): ObjectType
    {
        return $this->objectType;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getNewClassString(): string
    {
        return $this->newClassString;
    }
}
