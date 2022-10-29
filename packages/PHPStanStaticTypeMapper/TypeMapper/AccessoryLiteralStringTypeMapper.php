<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Accessory\AccessoryLiteralStringType;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;

/**
 * @implements TypeMapperInterface<AccessoryLiteralStringType>
 */
final class AccessoryLiteralStringTypeMapper implements TypeMapperInterface
{
    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return AccessoryLiteralStringType::class;
    }

    /**
     * @param AccessoryLiteralStringType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        return new IdentifierTypeNode('literal-string');
    }

    /**
     * @param AccessoryLiteralStringType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        return new Name('string');
    }
}
