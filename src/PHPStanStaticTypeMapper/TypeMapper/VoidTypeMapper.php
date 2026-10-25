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
use Rector\ValueObject\PhpVersion;

/**
 * @implements TypeMapperInterface<VoidType>
 */
final readonly class VoidTypeMapper implements TypeMapperInterface
{
    private const string VOID = 'void';

    public function __construct(
        private PhpVersionProvider $phpVersionProvider
    ) {
    }

    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [VoidType::class];
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
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersion::PHP_71)) {
            return null;
        }

        if (in_array($typeKind, [TypeKind::PARAM, TypeKind::PROPERTY, TypeKind::UNION], true)) {
            return null;
        }

        return new Identifier(self::VOID);
    }
}
