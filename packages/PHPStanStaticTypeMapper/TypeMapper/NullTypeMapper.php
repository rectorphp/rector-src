<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;

/**
 * @implements TypeMapperInterface<NullType>
 */
final class NullTypeMapper implements TypeMapperInterface
{
    public function __construct(private readonly PhpVersionProvider $phpVersionProvider)
    {
    }

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
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        return $type->toPhpDocNode();
    }

    /**
     * @param TypeKind::* $typeKind
     * @param NullType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        // ref https://3v4l.org/kI7KP
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::NULL_FALSE_TRUE_STANDALONE_TYPE)) {
            return null;
        }

        return new Identifier('null');
    }
}
