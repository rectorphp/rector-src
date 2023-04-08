<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;

/**
 * @implements TypeMapperInterface<NullType>
 */
final class NullTypeMapper implements TypeMapperInterface
{
    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return NullType::class;
    }

    /**
     * @param NullType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        return new IdentifierTypeNode('null');
    }

    /**
     * @param NullType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if ($typeKind === TypeKind::PROPERTY) {
            return null;
        }

        if ($typeKind === TypeKind::PARAM) {
            return null;
        }

        // return type cannot be only null
        if ($typeKind === TypeKind::RETURN) {
            return null;
        }

        return new Identifier('null');
    }
}
