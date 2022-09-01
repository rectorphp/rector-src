<?php

declare(strict_types=1);

namespace Rector\Core\Util\Reflection;

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
            $reflectionClass = new \ReflectionClass($object);
            $object = $reflectionClass->newInstanceWithoutConstructor();
        }

        $methodReflection = $this->createAccessibleMethodReflection($object, $methodName);

        return $methodReflection->invokeArgs($object, $arguments);
    }

    /**
     * @param object|class-string $object
     */
    public function callPrivateMethodWithReference(object|string $object, string $methodName, mixed $argument): mixed
    {
        if (is_string($object)) {
            $reflectionClass = new \ReflectionClass($object);
            $object = $reflectionClass->newInstanceWithoutConstructor();
        }

        $methodReflection = $this->createAccessibleMethodReflection($object, $methodName);
        $methodReflection->invokeArgs($object, [&$argument]);

        return $argument;
    }

    private function createAccessibleMethodReflection(object $object, string $methodName): \ReflectionMethod
    {
        $reflectionMethod = new \ReflectionMethod($object::class, $methodName);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }
}
