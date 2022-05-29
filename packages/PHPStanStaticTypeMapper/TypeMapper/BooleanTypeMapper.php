<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Type;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;

/**
 * @implements TypeMapperInterface<BooleanType>
 */
final class BooleanTypeMapper implements TypeMapperInterface
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
        return BooleanType::class;
    }

    /**
     * @param BooleanType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        if ($this->isFalseBooleanTypeWithUnion($type)) {
            return new IdentifierTypeNode('false');
        }

        if ($type instanceof ConstantBooleanType) {
            // cannot be parent of union
            return new IdentifierTypeNode('true');
        }

        return new IdentifierTypeNode('bool');
    }

    /**
     * @param BooleanType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::SCALAR_TYPES)) {
            return null;
        }

        if ($this->isFalseBooleanTypeWithUnion($type)) {
            return new Name('false');
        }

        return new Name('bool');
    }

    private function isFalseBooleanTypeWithUnion(Type $type): bool
    {
        if (! $type instanceof ConstantBooleanType) {
            return false;
        }

        if ($type->getValue()) {
            return false;
        }

        return $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES);
    }
}
