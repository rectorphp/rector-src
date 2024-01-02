<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class RemoveMethodCallParam
{
    public function __construct(
        private string $class,
        private string $methodName,
        private int $paramPosition
    ) {
        RectorAssert::className($class);
        RectorAssert::methodName($methodName);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getParamPosition(): int
    {
        return $this->paramPosition;
    }
}
