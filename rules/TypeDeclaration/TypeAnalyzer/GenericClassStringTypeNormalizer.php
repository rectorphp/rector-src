<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\ThisType;
use PHPStan\Type\UnionType;
use PHPStan\Type\TypeTraverser;
use Rector\Core\Configuration\Option;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class GenericClassStringTypeNormalizer
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private ParameterProvider $parameterProvider
    ) {
    }

    public function normalize(Type $type): Type
    {
        $isAutoImport = $this->parameterProvider->provideParameter(Option::AUTO_IMPORT_NAMES);
        return TypeTraverser::map($type, function (Type $type, $callback) use ($isAutoImport): Type {
            if (! $type instanceof ConstantStringType) {
                if ($type instanceof UnionType) {
                    $returnTypes = $type->getTypes();
                    $types = [];
                    $hasFullyQualifiedObjectType = false;
                    foreach ($returnTypes as $returnType) {
                        if ($this->isAutoImportFullyQualifiedObjectType($returnType, $isAutoImport)) {
                            /** @var FullyQualifiedObjectType $returnType */
                            $types[] = new GenericClassStringType(new ObjectType($returnType->getClassName()));
                            $hasFullyQualifiedObjectType = true;
                            continue;
                        }

                        $types[] = $returnType;
                    }

                    if ($hasFullyQualifiedObjectType) {
                        return new UnionType($types);
                    }

                    return $type;
                }

                if ($this->isAutoImportFullyQualifiedObjectType($type, $isAutoImport)) {
                    /** @var FullyQualifiedObjectType $type */
                    return new GenericClassStringType(new ObjectType($type->getClassName()));
                }

                return $callback($type);
            }

            // skip string that look like classe
            if ($type->getValue() === 'error') {
                return $callback($type);
            }

            if (! $this->reflectionProvider->hasClass($type->getValue())) {
                return $callback($type);
            }

            return new GenericClassStringType(new ObjectType($type->getValue()));
        });
    }

    private function isAutoImportFullyQualifiedObjectType(Type $type, bool $isAutoImport): bool
    {
        return $isAutoImport && $type instanceof FullyQualifiedObjectType;
    }
}
