<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\Type;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements TypeMapperInterface<ClassStringType>
 */
final class ClassStringTypeMapper implements TypeMapperInterface
{
    private GenericClassStringTypeMapper $genericClassStringTypeMapper;

    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    #[Required]
    public function autowire(GenericClassStringTypeMapper $genericClassStringTypeMapper): void
    {
        $this->genericClassStringTypeMapper = $genericClassStringTypeMapper;
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return ClassStringType::class;
    }

    /**
     * @param ClassStringType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        if ($type instanceof GenericClassStringType) {
            return $this->genericClassStringTypeMapper->mapToPHPStanPhpDocTypeNode($type, $typeKind);
        }

        return new IdentifierTypeNode('class-string');
    }

    /**
     * @param ClassStringType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::SCALAR_TYPES)) {
            return null;
        }

        return new Name('string');
    }
}
