<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Type;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @implements TypeMapperInterface<BooleanType>
 */
final readonly class BooleanTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider
    ) {
    }

    public function getNodeClass(): string
    {
        return BooleanType::class;
    }

    /**
     * @param BooleanType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        return $type->toPhpDocNode();
    }

    /**
     * @param BooleanType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::SCALAR_TYPES)) {
            return null;
        }

        if ($typeKind === TypeKind::PROPERTY) {
            return new Identifier('bool');
        }

        if ($typeKind === TypeKind::UNION && $type->isFalse()->yes()) {
            return new Identifier('false');
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::NULL_FALSE_TRUE_STANDALONE_TYPE)) {
            return new Identifier('bool');
        }

        if ($type->isTrue()->yes()) {
            return new Identifier('true');
        }

        if ($type->isFalse()->yes()) {
            return new Identifier('false');
        }

        return new Identifier('bool');
    }
}
