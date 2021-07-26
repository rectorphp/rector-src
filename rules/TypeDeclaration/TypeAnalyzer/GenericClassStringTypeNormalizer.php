<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use PHPStan\Type\UnionType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\ClassStringType;

final class GenericClassStringTypeNormalizer
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private ParameterProvider $parameterProvider
    ) {
    }

    public function normalize(Type $type): Type
    {
        $type = TypeTraverser::map($type, function (Type $type, $callback): Type {
            if (! $type instanceof ConstantStringType) {
                return $callback($type);
            }

            $value = $type->getValue();

            // skip string that look like classe
            if ($value === 'error') {
                return $callback($type);
            }

            if (! $this->reflectionProvider->hasClass($value)) {
                return $callback($type);
            }

            return $this->resolveStringType($value);
        });

        if ($type instanceof UnionType) {
            return $this->resolveUnionType($type);
        }

        return $type;
    }

    private function resolveUnionType(UnionType $type): Type
    {
        $unionTypes       = $type->getTypes();
        $isAllClassString = true;

        foreach ($unionTypes as $unionType) {
            if (! $unionType instanceof ArrayType) {
                $isAllClassString = false;
                break;
            }

            $keyType  = $unionType->getKeyType();
            $itemType = $unionType->getItemType();

            if (! $keyType instanceof MixedType && ! $keyType instanceof ConstantIntegerType) {
                $isAllClassString = false;
                break;
            }

            if (! $itemType instanceof ClassStringType) {
                $isAllClassString = false;
                break;
            }
        }

        if ($isAllClassString) {
            return new ArrayType(new MixedType(), new ClassStringType());
        }

        return $type;
    }

    private function resolveStringType(string $value): GenericClassStringType | StringType
    {
        $classReflection = $this->reflectionProvider->getClass($value);
        if ($classReflection->isBuiltIn()) {
            return new GenericClassStringType(new ObjectType($value));
        }
        if (str_contains($value, '\\')) {
            return new GenericClassStringType(new ObjectType($value));
        }
        return new StringType();
    }
}
