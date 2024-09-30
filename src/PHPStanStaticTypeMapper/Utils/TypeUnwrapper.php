<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\Utils;

use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\CallableType;
use PHPStan\Reflection\ParameterReflection;

final class TypeUnwrapper
{
    public function unwrapFirstObjectTypeFromUnionType(Type $type): Type
    {
        if (! $type instanceof UnionType) {
            return $type;
        }

        foreach ($type->getTypes() as $unionedType) {
            if (! $unionedType instanceof TypeWithClassName) {
                continue;
            }

            return $unionedType;
        }

        return $type;
    }

    public function unwrapFirstCallableTypeFromUnionType(Type $type): ?Type
    {
        if (! $type instanceof UnionType) {
            return $type;
        }

        foreach ($type->getTypes() as $unionedType) {
            if (! $unionedType instanceof CallableType) {
                continue;
            }

            return $unionedType;
        }

        return $type;
    }

    public function isIterableTypeValue(string $className, Type $object): bool
    {
        if (! $object instanceof TypeWithClassName) {
            return false;
        }

        // get the namespace from $className
        $classNamespace = $this->namespace($className);

        // get the namespace from $parameterReflection
        $reflectionNamespace = $this->namespace($object->getClassName());

        // then match with
        return $reflectionNamespace === $classNamespace && str_ends_with($object->getClassName(), '\TValue');
    }

    public function isIterableTypeKey(string $className, Type $objectType): bool
    {
        if (! $objectType instanceof TypeWithClassName) {
            return false;
        }

        // get the namespace from $className
        $classNamespace = $className;

        // get the namespace from $parameterReflection
        $reflectionNamespace = $objectType->getClassName();

        // then match with
        return $reflectionNamespace === $classNamespace && str_ends_with($objectType->getClassName(), '\TKey');
    }

    public function removeNullTypeFromUnionType(UnionType $unionType): Type
    {
        return TypeCombinator::removeNull($unionType);
    }

    private function namespace(string $class): string
    {
        return implode('\\', array_slice(explode('\\', $class), 0, -1));
    }
}
