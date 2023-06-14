<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;

final class ArrayShapeTypeMapper
{
    public function __construct(
        private readonly PHPStanStaticTypeMapper $phpStanStaticTypeMapper,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function mapConstantArrayType(ConstantArrayType $constantArrayType): ArrayShapeNode|ArrayType|null
    {
        // empty array
        if ($constantArrayType->getKeyType() instanceof NeverType) {
            return new ArrayType(new MixedType(), new MixedType());
        }

        $arrayShapeItemNodes = [];

        foreach ($constantArrayType->getKeyTypes() as $index => $keyType) {
            // not real array shape
            if (! $keyType instanceof ConstantStringType) {
                return null;
            }

            $keyValue = $keyType->getValue();

            if ($this->reflectionProvider->hasClass($keyValue)) {
                return null;
            }

            $keyDocTypeNode = new IdentifierTypeNode($keyValue);
            $valueType = $constantArrayType->getValueTypes()[$index];

            $valueDocTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($valueType);

            $arrayShapeItemNodes[] = new ArrayShapeItemNode(
                $keyDocTypeNode,
                $constantArrayType->isOptionalKey($index),
                $valueDocTypeNode
            );
        }

        return new ArrayShapeNode($arrayShapeItemNodes);
    }
}
