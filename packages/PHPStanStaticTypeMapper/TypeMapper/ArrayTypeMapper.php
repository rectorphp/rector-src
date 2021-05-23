<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\SpacingAwareArrayTypeNode;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\UnionTypeCommonTypeNarrower;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @see \Rector\Tests\PHPStanStaticTypeMapper\TypeMapper\ArrayTypeMapperTest
 */
final class ArrayTypeMapper implements TypeMapperInterface
{
    /**
     * @var string
     */
    public const HAS_GENERIC_TYPE_PARENT = 'has_generic_type_parent';

    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    private UnionTypeCommonTypeNarrower $unionTypeCommonTypeNarrower;

    private ReflectionProvider $reflectionProvider;

    // To avoid circular dependency

    #[Required]
    public function autowireArrayTypeMapper(
        PHPStanStaticTypeMapper $phpStanStaticTypeMapper,
        UnionTypeCommonTypeNarrower $unionTypeCommonTypeNarrower,
        ReflectionProvider $reflectionProvider
    ): void {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
        $this->unionTypeCommonTypeNarrower = $unionTypeCommonTypeNarrower;
        $this->reflectionProvider = $reflectionProvider;
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return ArrayType::class;
    }

    /**
     * @param ArrayType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $itemType = $type->getItemType();

        if ($itemType instanceof UnionType && ! $type instanceof ConstantArrayType) {
            return $this->createArrayTypeNodeFromUnionType($itemType);
        }

        if ($itemType instanceof ArrayType && $this->isGenericArrayCandidate($itemType)) {
            return $this->createGenericArrayType($type, true);
        }

        if ($this->isGenericArrayCandidate($type)) {
            return $this->createGenericArrayType($type, true);
        }

        $narrowedTypeNode = $this->narrowConstantArrayTypeOfUnionType($type, $itemType);
        if ($narrowedTypeNode instanceof TypeNode) {
            return $narrowedTypeNode;
        }

        $itemTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($itemType);

        return new SpacingAwareArrayTypeNode($itemTypeNode);
    }

    /**
     * @param ArrayType $type
     */
    public function mapToPhpParserNode(Type $type, ?string $kind = null): ?Node
    {
        return new Name('array');
    }

    private function createArrayTypeNodeFromUnionType(UnionType $unionType): SpacingAwareArrayTypeNode
    {
        $unionedArrayType = [];
        foreach ($unionType->getTypes() as $unionedType) {
            $typeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($unionedType);
            $unionedArrayType[(string) $typeNode] = $typeNode;
        }

        if (count($unionedArrayType) > 1) {
            return new SpacingAwareArrayTypeNode(new BracketsAwareUnionTypeNode($unionedArrayType));
        }

        /** @var TypeNode $arrayType */
        $arrayType = array_shift($unionedArrayType);
        return new SpacingAwareArrayTypeNode($arrayType);
    }

    private function isGenericArrayCandidate(ArrayType $arrayType): bool
    {
        if ($arrayType->getKeyType() instanceof MixedType) {
            return false;
        }

        if ($this->isClassStringArrayType($arrayType)) {
            return true;
        }

        // skip simple arrays, like "string[]", from converting to obvious "array<int, string>"
        if ($this->isIntegerKeyAndNonNestedArray($arrayType)) {
            return false;
        }

        if ($arrayType->getKeyType() instanceof NeverType) {
            return false;
        }

        // make sure the integer key type is not natural/implicit array int keys
        $keysArrayType = $arrayType->getKeysArray();
        if (! $keysArrayType instanceof ConstantArrayType) {
            return true;
        }

        foreach ($keysArrayType->getValueTypes() as $key => $keyType) {
            if (! $keyType instanceof ConstantIntegerType) {
                return true;
            }

            if ($key !== $keyType->getValue()) {
                return true;
            }
        }

        return false;
    }

    private function createGenericArrayType(ArrayType $arrayType, bool $withKey = false): GenericTypeNode
    {
        $itemTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($arrayType->getItemType());
        $identifierTypeNode = new IdentifierTypeNode('array');

        // is class-string[] list only
        if ($this->isClassStringArrayType($arrayType)) {
            $withKey = false;
        }

        if ($withKey) {
            $keyTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($arrayType->getKeyType());
            $genericTypes = [$keyTypeNode, $itemTypeNode];
        } else {
            $genericTypes = [$itemTypeNode];
        }

        // @see https://github.com/phpstan/phpdoc-parser/blob/98a088b17966bdf6ee25c8a4b634df313d8aa531/tests/PHPStan/Parser/PhpDocParserTest.php#L2692-L2696

        foreach ($genericTypes as $genericType) {
            /** @var \PHPStan\PhpDocParser\Ast\Node $genericType */
            $genericType->setAttribute(self::HAS_GENERIC_TYPE_PARENT, $withKey);
        }

        $identifierTypeNode->setAttribute(self::HAS_GENERIC_TYPE_PARENT, $withKey);
        return new GenericTypeNode($identifierTypeNode, $genericTypes);
    }

    private function isIntegerKeyAndNonNestedArray(ArrayType $arrayType): bool
    {
        if (! $arrayType->getKeyType() instanceof IntegerType) {
            return false;
        }

        return ! $arrayType->getItemType() instanceof ArrayType;
    }

    private function narrowConstantArrayTypeOfUnionType(ArrayType $arrayType, Type $itemType): ?TypeNode
    {
        if ($arrayType instanceof ConstantArrayType && $itemType instanceof UnionType) {
            $narrowedItemType = $this->unionTypeCommonTypeNarrower->narrowToSharedObjectType($itemType);
            if ($narrowedItemType instanceof ObjectType) {
                $itemTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($narrowedItemType);
                return new SpacingAwareArrayTypeNode($itemTypeNode);
            }

            $narrowedItemType = $this->unionTypeCommonTypeNarrower->narrowToGenericClassStringType($itemType);
            if ($narrowedItemType instanceof GenericClassStringType) {
                return $this->createTypeNodeFromGenericClassStringType($narrowedItemType);
            }
        }

        return null;
    }

    private function createTypeNodeFromGenericClassStringType(
        GenericClassStringType $genericClassStringType
    ): TypeNode {
        $genericType = $genericClassStringType->getGenericType();
        if ($genericType instanceof ObjectType && ! $this->reflectionProvider->hasClass($genericType->getClassName())) {
            return new IdentifierTypeNode($genericType->getClassName());
        }

        $itemTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($genericClassStringType);

        return new GenericTypeNode(new IdentifierTypeNode('array'), [$itemTypeNode]);
    }

    private function isClassStringArrayType(ArrayType $arrayType): bool
    {
        if ($arrayType->getKeyType() instanceof MixedType) {
            return $arrayType->getItemType() instanceof GenericClassStringType;
        }

        if ($arrayType->getKeyType() instanceof ConstantIntegerType) {
            return $arrayType->getItemType() instanceof GenericClassStringType;
        }

        return false;
    }
}
