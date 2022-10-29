<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements TypeMapperInterface<GenericClassStringType>
 */
final class GenericClassStringTypeMapper implements TypeMapperInterface
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    #[Required]
    public function autowire(PHPStanStaticTypeMapper $phpStanStaticTypeMapper): void
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return GenericClassStringType::class;
    }

    /**
     * @param GenericClassStringType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        $attributeAwareIdentifierTypeNode = new IdentifierTypeNode('class-string');
        $genericType = $this->resolveGenericObjectType($type);

        $genericTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($genericType, $typeKind);
        return new GenericTypeNode($attributeAwareIdentifierTypeNode, [$genericTypeNode]);
    }

    /**
     * @param GenericClassStringType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        return new Name('string');
    }

    private function resolveGenericObjectType(GenericClassStringType $genericClassStringType): ObjectType|Type
    {
        $genericType = $genericClassStringType->getGenericType();

        if (! $genericType instanceof ObjectType) {
            return $genericType;
        }

        $className = $genericType->getClassName();
        $className = $this->normalizeType($className);
        return new ObjectType($className);
    }

    private function normalizeType(string $classType): string
    {
        if (is_a($classType, Expr::class, true)) {
            return Expr::class;
        }

        if (is_a($classType, Node::class, true)) {
            return Node::class;
        }

        return $classType;
    }
}
