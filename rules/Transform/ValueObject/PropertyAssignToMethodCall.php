<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final class PropertyAssignToMethodCall
{
    public function __construct(
        private readonly string $class,
        private readonly string $oldPropertyName,
        private readonly string $newMethodName
    ) {
        RectorAssert::className($class);
        RectorAssert::propertyName($oldPropertyName);
        RectorAssert::methodName($newMethodName);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getOldPropertyName(): string
    {
        return $this->oldPropertyName;
    }

    public function getNewMethodName(): string
    {
        return $this->newMethodName;
    }
}
