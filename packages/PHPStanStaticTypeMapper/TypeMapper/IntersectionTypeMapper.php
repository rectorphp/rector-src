<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements TypeMapperInterface<IntersectionType>
 */
final class IntersectionTypeMapper implements TypeMapperInterface
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ObjectTypeMapper $objectTypeMapper
    ) {
    }

    #[Required]
    public function __constr(PHPStanStaticTypeMapper $phpStanStaticTypeMapper): void
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return IntersectionType::class;
    }

    /**
     * @param IntersectionType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $type = TypeTraverser::map($type, static function (Type $type, callable $traverse): Type {
            if ($type instanceof ObjectType) {
                return $this->objectTypeMapper->mapToPHPStanPhpDocTypeNode($type);
            }

            return $traverse($type);
        });

        return $type->toPhpDocNode();
    }

    /**
     * @param IntersectionType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::INTERSECTION_TYPES)) {
            return null;
        }

        $intersectionedTypeNodes = [];
        foreach ($type->getTypes() as $intersectionedType) {
            $resolvedType = $this->phpStanStaticTypeMapper->mapToPhpParserNode($intersectionedType, $typeKind);

            if (! $resolvedType instanceof Name && ! $resolvedType instanceof Identifier) {
                return null;
            }

            $resolvedTypeName = (string) $resolvedType;

            /**
             * ObjectWithoutClassType can happen when use along with \PHPStan\Type\Accessory\HasMethodType
             * Use "object" as returned type
             */
            if ($intersectionedType instanceof ObjectWithoutClassType) {
                return $resolvedType;
            }

            if (! $intersectionedType instanceof ObjectType) {
                return null;
            }

            if (! $this->reflectionProvider->hasClass($resolvedTypeName)) {
                return null;
            }

            $intersectionedTypeNodes[] = $resolvedType;
        }

        if ($intersectionedTypeNodes === []) {
            return null;
        }

        if (count($intersectionedTypeNodes) === 1) {
            return current($intersectionedTypeNodes);
        }

        return new Node\IntersectionType($intersectionedTypeNodes);
    }
}
