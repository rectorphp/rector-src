<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\IterableType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

final class IterableTypeAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function isIterableType(Type $type): bool
    {
        if ($this->isUnionOfIterableTypes($type)) {
            return true;
        }

        if ($type->isArray()->yes()) {
            return true;
        }

        if ($type instanceof IterableType) {
            return true;
        }

        if ($type instanceof GenericObjectType) {
            if (! $this->reflectionProvider->hasClass($type->getClassName())) {
                return false;
            }

            $genericObjectTypeClassReflection = $this->reflectionProvider->getClass($type->getClassName());
            if ($genericObjectTypeClassReflection->implementsInterface('Traversable')) {
                return true;
            }
        }

        return false;
    }

    private function isUnionOfIterableTypes(Type $type): bool
    {
        if (! $type instanceof UnionType) {
            return false;
        }

        foreach ($type->getTypes() as $unionedType) {
            // nullable union is allowed
            if ($unionedType instanceof NullType) {
                continue;
            }

            if (! $this->isIterableType($unionedType)) {
                return false;
            }
        }

        return true;
    }
}
