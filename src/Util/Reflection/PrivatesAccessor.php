<?php

declare(strict_types=1);

namespace Rector\Core\Util\Reflection;

use Rector\Core\Exception\Reflection\InvalidPrivatePropertyTypeException;
use Rector\Core\Exception\Reflection\MissingPrivatePropertyException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @see \Rector\Core\Tests\Util\Reflection\PrivatesAccessorTest
 */
final class PrivatesAccessor
{
    /**
     * @param object|class-string $object
     * @param mixed[] $arguments
     * @api
     */
    public function callPrivateMethod(object|string $object, string $methodName, array $arguments): mixed
    {
        if (is_string($object)) {
            $reflectionClass = new ReflectionClass($object);
            $object = $reflectionClass->newInstanceWithoutConstructor();
        }

        $reflectionMethod = $this->createAccessibleMethodReflection($object, $methodName);

        return $reflectionMethod->invokeArgs($object, $arguments);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $valueClassName
     * @return T
     */
    public function getPrivatePropertyOfClass(object $object, string $propertyName, string $valueClassName): object
    {
        $value = $this->getPrivateProperty($object, $propertyName);
        if ($value instanceof $valueClassName) {
            return $value;
        }

        $errorMessage = sprintf('The type "%s" is required, but "%s" type given', $valueClassName, $value::class);
        throw new InvalidPrivatePropertyTypeException($errorMessage);
    }

    public function getPrivateProperty(object $object, string $propertyName): mixed
    {
        $reflectionProperty = $this->resolvePropertyReflection($object, $propertyName);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $valueClassName
     * @param T $value
     */
    public function setPrivatePropertyOfClass(
        object $object,
        string $propertyName,
        mixed $value,
        string $valueClassName
    ): void {
        if ($value instanceof $valueClassName) {
            $this->setPrivateProperty($object, $propertyName, $value);
            return;
        }

        $errorMessage = sprintf('The type "%s" is required, but "%s" type given', $valueClassName, $value::class);
        throw new InvalidPrivatePropertyTypeException($errorMessage);
    }

    public function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflectionProperty = $this->resolvePropertyReflection($object, $propertyName);
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($object, $value);
    }

    private function createAccessibleMethodReflection(object $object, string $methodName): ReflectionMethod
    {
        $reflectionClass = new ReflectionClass($object::class);

        $reflectionMethod = $reflectionClass->getMethod($methodName);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }

    private function resolvePropertyReflection(object $object, string $propertyName): ReflectionProperty
    {
        if (property_exists($object, $propertyName)) {
            return new ReflectionProperty($object, $propertyName);
        }

        $parentClass = get_parent_class($object);
        if ($parentClass !== false) {
            return new ReflectionProperty($parentClass, $propertyName);
        }

        $errorMessage = sprintf('Property "$%s" was not found in "%s" class', $propertyName, $object::class);
        throw new MissingPrivatePropertyException($errorMessage);
    }
}
