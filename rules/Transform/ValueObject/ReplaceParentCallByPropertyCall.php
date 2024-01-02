<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class ReplaceParentCallByPropertyCall
{
    public function __construct(
        private string $class,
        private string $method,
        private string $property
    ) {
        RectorAssert::className($class);
        RectorAssert::methodName($method);
        RectorAssert::propertyName($property);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
