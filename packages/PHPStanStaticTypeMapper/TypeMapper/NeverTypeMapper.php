<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;

/**
 * @implements TypeMapperInterface<NeverType>
 */
final class NeverTypeMapper implements TypeMapperInterface
{
    public function __construct(private readonly PhpVersionProvider $phpVersionProvider)
    {
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return NeverType::class;
    }

    /**
     * @param TypeKind::* $typeKind
     * @param NeverType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        if ($typeKind === TypeKind::RETURN) {
            return new IdentifierTypeNode('never');
        }

        return new IdentifierTypeNode('mixed');
    }

    /**
     * @param NeverType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if ($typeKind !== TypeKind::RETURN) {
            return null;
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::NEVER_TYPE)) {
            return null;
        }

        return new Identifier('never');
    }
}
