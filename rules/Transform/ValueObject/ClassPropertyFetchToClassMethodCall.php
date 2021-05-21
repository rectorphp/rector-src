<?php
declare(strict_types=1);


namespace Rector\Transform\ValueObject;


use PHPStan\Type\ObjectType;

final class ClassPropertyFetchToClassMethodCall
{
    public function __construct(
        private string $class,
        private string $property,
        private string $method,
    ) {
    }

    public function getObjecType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
