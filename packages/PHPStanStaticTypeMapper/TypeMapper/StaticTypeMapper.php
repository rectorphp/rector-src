<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\Type\ThisType;
use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\SelfStaticType;
use Rector\StaticTypeMapper\ValueObject\Type\SimpleStaticType;

/**
 * @see \Rector\Tests\NodeTypeResolver\StaticTypeMapper\StaticTypeMapperTest
 *
 * @implements TypeMapperInterface<StaticType>
 */
final class StaticTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return StaticType::class;
    }

    /**
     * @param StaticType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        return $type->toPhpDocNode();
    }

    /**
     * @param StaticType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        // special case, for autocomplete of return type
        if ($type instanceof SimpleStaticType) {
            return $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::STATIC_RETURN_TYPE)
                ? new Name(ObjectReference::STATIC)
                : new Name(ObjectReference::SELF);
        }

        if ($type instanceof SelfStaticType) {
            return new Name(ObjectReference::SELF);
        }

        if ($type instanceof ThisType) {
            return new Name(ObjectReference::SELF);
        }

        if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::STATIC_RETURN_TYPE)) {
            return new Name(ObjectReference::STATIC);
        }

        return new Name(ObjectReference::SELF);
    }
}
