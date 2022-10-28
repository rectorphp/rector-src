<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareIntersectionTypeNode;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
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
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    #[Required]
    public function autowire(PHPStanStaticTypeMapper $phpStanStaticTypeMapper): void
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
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        $intersectionTypesNodes = [];

        foreach ($type->getTypes() as $intersectionedType) {
            $intersectionTypesNodes[] = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode(
                $intersectionedType,
                $typeKind
            );
        }

        $intersectionTypesNodes = array_unique($intersectionTypesNodes);

        if (count($intersectionTypesNodes) === 1) {
            return $intersectionTypesNodes[0];
        }

        return new BracketsAwareIntersectionTypeNode($intersectionTypesNodes);
    }

    /**
     * @param IntersectionType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::INTERSECTION_TYPES)) {
            return $this->matchMockObjectType($type);
        }

        $intersectionedTypeNodes = [];
        foreach ($type->getTypes() as $intersectionedType) {
            $resolvedType = $this->resolveType($intersectionedType, $typeKind);

            if (! $resolvedType instanceof Name) {
                continue;
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

    /**
     * @param TypeKind::* $typeKind
     */
    private function resolveType(Type $intersectionedType, string $typeKind): ?Name
    {
        $resolvedType = $this->phpStanStaticTypeMapper->mapToPhpParserNode($intersectionedType, $typeKind);
        if (! $resolvedType instanceof Name) {
            return null;
        }

        // $this->reflectionProvider->hasClass() returns true for iterable, so check early
        if ((string) $resolvedType === 'iterable') {
            return null;
        }

        if (! $this->reflectionProvider->hasClass((string) $resolvedType)) {
            return null;
        }

        return $resolvedType;
    }

    private function matchMockObjectType(IntersectionType $intersectionType): ?FullyQualified
    {
        // return mock object as the strict one
        foreach ($intersectionType->getTypes() as $intersectionedType) {
            if (! $intersectionedType instanceof ObjectType) {
                continue;
            }

            if ($intersectionedType->getClassName() === 'PHPUnit\Framework\MockObject\MockObject') {
                return new FullyQualified($intersectionedType->getClassName());
            }
        }

        return null;
    }
}
