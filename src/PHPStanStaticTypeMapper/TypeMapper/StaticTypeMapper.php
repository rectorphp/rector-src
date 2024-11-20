<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use Rector\Enum\ObjectReference;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\ValueObject\Type\SelfStaticType;
use Rector\StaticTypeMapper\ValueObject\Type\SimpleStaticType;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @see \Rector\Tests\NodeTypeResolver\StaticTypeMapper\StaticTypeMapperTest
 *
 * @implements TypeMapperInterface<StaticType>
 */
final readonly class StaticTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider
    ) {
    }

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
     * @param SimpleStaticType|StaticType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): Name
    {
        if ($type instanceof SelfStaticType) {
            return new Name(ObjectReference::SELF);
        }

        if ($typeKind !== TypeKind::RETURN) {
            return new Name(ObjectReference::SELF);
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::STATIC_RETURN_TYPE)) {
            return new Name(ObjectReference::SELF);
        }

        return new Name(ObjectReference::STATIC);
    }
}
