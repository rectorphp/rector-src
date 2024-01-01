<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @implements TypeMapperInterface<VoidType>
 */
final class VoidTypeMapper implements TypeMapperInterface
{
    /**
     * @var string
     */
    private const VOID = 'void';

    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return VoidType::class;
    }

    /**
     * @param VoidType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        return $type->toPhpDocNode();
    }

    /**
     * @param TypeKind::* $typeKind
     * @param VoidType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::VOID_TYPE)) {
            return null;
        }

        if (in_array($typeKind, [TypeKind::PARAM, TypeKind::PROPERTY], true)) {
            return null;
        }

        return new Identifier(self::VOID);
    }
}
