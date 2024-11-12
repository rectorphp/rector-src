<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\Utils;

use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\StaticTypeMapper\Resolver\ClassNameFromObjectTypeResolver;

final class TypeUnwrapper
{
    public function unwrapFirstObjectTypeFromUnionType(Type $type): Type
    {
        if (! $type instanceof UnionType) {
            return $type;
        }

        foreach ($type->getTypes() as $unionedType) {
            $className = ClassNameFromObjectTypeResolver::resolve($unionedType);
            if ($className === null) {
                continue;
            }

            return $unionedType;
        }

        return $type;
    }

    public function unwrapFirstCallableTypeFromUnionType(Type $type): Type
    {
        if (! $type instanceof UnionType) {
            return $type;
        }

        foreach ($type->getTypes() as $unionedType) {
            if (! $unionedType->isCallable()->yes()) {
                continue;
            }

            return $unionedType;
        }

        return $type;
    }

    public function isIterableTypeValue(string $className, Type $type): bool
    {
        $typeClassName = ClassNameFromObjectTypeResolver::resolve($type);
        if ($typeClassName === null) {
            return false;
        }

        // get the namespace from $className
        $classNamespace = $this->namespace($className);

        // get the namespace from $parameterReflection
        $reflectionNamespace = $this->namespace($typeClassName);

        // then match with
        return $reflectionNamespace === $classNamespace && str_ends_with($typeClassName, '\TValue');
    }

    public function isIterableTypeKey(string $className, Type $type): bool
    {
        $typeClassName = ClassNameFromObjectTypeResolver::resolve($type);
        if ($typeClassName === null) {
            return false;
        }

        // get the namespace from $className
        $classNamespace = $this->namespace($className);

        // get the namespace from $parameterReflection
        $reflectionNamespace = $this->namespace($typeClassName);

        // then match with
        return $reflectionNamespace === $classNamespace && str_ends_with($typeClassName, '\TKey');
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
