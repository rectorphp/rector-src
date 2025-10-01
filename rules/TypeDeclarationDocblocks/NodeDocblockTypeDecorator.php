<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Privatization\TypeManipulator\TypeNormalizer;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class NodeDocblockTypeDecorator
{
    public function __construct(
        private TypeNormalizer $typeNormalizer,
        private StaticTypeMapper $staticTypeMapper,
        private PhpDocTypeChanger $phpDocTypeChanger
    ) {
    }

    public function decorateGenericIterableParamType(
        Type $type,
        PhpDocInfo $phpDocInfo,
        ClassMethod $classMethod,
        Param $param,
        string $parameterName
    ): bool {
        if ($this->isBareMixedType($type)) {
            // no value
            return false;
        }

        $typeNode = $this->createTypeNode($type);

        // no value iterable type
        if ($typeNode instanceof IdentifierTypeNode) {
            return false;
        }

        $this->phpDocTypeChanger->changeParamTypeNode($classMethod, $phpDocInfo, $param, $parameterName, $typeNode);

        return true;
    }

    public function decorateGenericIterableReturnType(
        Type $type,
        PhpDocInfo $classMethodPhpDocInfo,
        ClassMethod $classMethod
    ): bool {
        if ($this->isBareMixedType($type)) {
            // no value
            return false;
        }

        $typeNode = $this->createTypeNode($type);

        // no value iterable type
        if ($typeNode instanceof IdentifierTypeNode) {
            return false;
        }

        $this->phpDocTypeChanger->changeReturnTypeNode($classMethod, $classMethodPhpDocInfo, $typeNode);

        return true;
    }

    public function decorateGenericIterableVarType(Type $type, PhpDocInfo $phpDocInfo, Property $property): bool
    {
        $typeNode = $this->createTypeNode($type);

        if ($this->isBareMixedType($type)) {
            // no value
            return false;
        }

        // no value iterable type
        if ($typeNode instanceof IdentifierTypeNode) {
            return false;
        }

        $this->phpDocTypeChanger->changeVarTypeNode($property, $phpDocInfo, $typeNode);

        return true;
    }

    private function createTypeNode(Type $type): TypeNode
    {
        $generalizedReturnType = $this->typeNormalizer->generalizeConstantTypes($type);

        // turn into rather generic short return type, to keep it open to extension later and readable to human
        $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($generalizedReturnType);

        if ($typeNode instanceof IdentifierTypeNode && $typeNode->name === 'mixed') {
            return new ArrayTypeNode($typeNode);
        }

        return $typeNode;
    }

    private function isBareMixedType(Type $type): bool
    {
        if ($type instanceof MixedType) {
            return true;
        }

        $normalizedResolvedParameterType = $this->typeNormalizer->generalizeConstantTypes($type);

        // most likely mixed, skip
        return $this->isArrayMixed($normalizedResolvedParameterType);
    }

    private function isArrayMixed(Type $type): bool
    {
        if (! $type instanceof ArrayType) {
            return false;
        }

        if ($type->getItemType() instanceof NeverType) {
            return true;
        }

        if (! $type->getItemType() instanceof MixedType) {
            return false;
        }

        return $type->getKeyType() instanceof IntegerType;
    }
}
