<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType as PhpParserUnionType;
use PhpParser\NodeAbstract;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\IterableType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\BoolUnionTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\UnionTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\UnionTypeCommonTypeNarrower;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;
use Rector\PHPStanStaticTypeMapper\ValueObject\UnionTypeAnalysis;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements TypeMapperInterface<UnionType>
 */
final class UnionTypeMapper implements TypeMapperInterface
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    public function __construct(
        private DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private PhpVersionProvider $phpVersionProvider,
        private UnionTypeAnalyzer $unionTypeAnalyzer,
        private BoolUnionTypeAnalyzer $boolUnionTypeAnalyzer,
        private UnionTypeCommonTypeNarrower $unionTypeCommonTypeNarrower,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    #[Required]
    public function autowireUnionTypeMapper(PHPStanStaticTypeMapper $phpStanStaticTypeMapper): void
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
    }

    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return UnionType::class;
    }

    /**
     * @param UnionType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, TypeKind $typeKind): TypeNode
    {
        $unionTypesNodes = [];
        $skipIterable = $this->shouldSkipIterable($type);

        foreach ($type->getTypes() as $unionedType) {
            if ($unionedType instanceof IterableType && $skipIterable) {
                continue;
            }

            $unionTypesNodes[] = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($unionedType, $typeKind);
        }

        $unionTypesNodes = array_unique($unionTypesNodes);
        return new BracketsAwareUnionTypeNode($unionTypesNodes);
    }

    /**
     * @param UnionType $type
     */
    public function mapToPhpParserNode(Type $type, TypeKind $typeKind): ?Node
    {
        $arrayNode = $this->matchArrayTypes($type);
        if ($arrayNode !== null) {
            return $arrayNode;
        }

        if ($this->boolUnionTypeAnalyzer->isNullableBoolUnionType(
            $type
        ) && ! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return new NullableType(new Name('bool'));
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES) && $this->isFalseBoolUnion(
            $type
        )) {
            // return new Bool
            return new Name('bool');
        }

        // special case for nullable
        $nullabledType = $this->matchTypeForNullableUnionType($type);
        if (! $nullabledType instanceof Type) {
            // use first unioned type in case of unioned object types
            return $this->matchTypeForUnionedObjectTypes($type, $typeKind);
        }

        // void cannot be nullable
        if ($nullabledType instanceof VoidType) {
            return null;
        }

        $nullabledTypeNode = $this->phpStanStaticTypeMapper->mapToPhpParserNode($nullabledType, $typeKind);
        if (! $nullabledTypeNode instanceof Node) {
            return null;
        }

        if ($nullabledTypeNode instanceof NullableType) {
            return $nullabledTypeNode;
        }

        if ($nullabledTypeNode instanceof PhpParserUnionType) {
            throw new ShouldNotHappenException();
        }

        return new NullableType($nullabledTypeNode);
    }

    private function shouldSkipIterable(UnionType $unionType): bool
    {
        $unionTypeAnalysis = $this->unionTypeAnalyzer->analyseForNullableAndIterable($unionType);
        if (! $unionTypeAnalysis instanceof UnionTypeAnalysis) {
            return false;
        }

        if (! $unionTypeAnalysis->hasIterable()) {
            return false;
        }

        return $unionTypeAnalysis->hasArray();
    }

    private function matchArrayTypes(UnionType $unionType): Name | NullableType | null
    {
        $unionTypeAnalysis = $this->unionTypeAnalyzer->analyseForNullableAndIterable($unionType);
        if (! $unionTypeAnalysis instanceof UnionTypeAnalysis) {
            return null;
        }

        $type = $unionTypeAnalysis->hasIterable() ? 'iterable' : 'array';
        if ($unionTypeAnalysis->isNullableType()) {
            return new NullableType($type);
        }

        return new Name($type);
    }

    private function matchTypeForNullableUnionType(UnionType $unionType): ?Type
    {
        if (count($unionType->getTypes()) !== 2) {
            return null;
        }

        $firstType = $unionType->getTypes()[0];
        $secondType = $unionType->getTypes()[1];

        if ($firstType instanceof NullType) {
            return $secondType;
        }

        if ($secondType instanceof NullType) {
            return $firstType;
        }

        return null;
    }

    private function hasObjectAndStaticType(PhpParserUnionType $phpParserUnionType): bool
    {
        $typeNames = $this->nodeNameResolver->getNames($phpParserUnionType->types);
        $diff = array_diff(['object', ObjectReference::STATIC()->getValue()], $typeNames);

        return $diff === [];
    }

    /**
     * @return Name|FullyQualified|PhpParserUnionType|NullableType|null
     */
    private function matchTypeForUnionedObjectTypes(UnionType $unionType, TypeKind $typeKind): ?Node
    {
        $phpParserUnionType = $this->matchPhpParserUnionType($unionType, $typeKind);
        if ($phpParserUnionType !== null) {
            if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
                // maybe all one type?
                if ($this->boolUnionTypeAnalyzer->isBoolUnionType($unionType)) {
                    return new Name('bool');
                }

                return null;
            }

            if ($this->hasObjectAndStaticType($phpParserUnionType)) {
                return null;
            }

            return $phpParserUnionType;
        }

        if ($this->boolUnionTypeAnalyzer->isBoolUnionType($unionType)) {
            return new Name('bool');
        }

        return $this->processResolveCompatibleObjectCandidates($unionType);
    }

    private function processResolveCompatibleObjectCandidates(UnionType $unionType): ?Node
    {
        // the type should be compatible with all other types, e.g. A extends B, B
        $compatibleObjectType = $this->resolveCompatibleObjectCandidate($unionType);
        if ($compatibleObjectType instanceof UnionType) {
            $type = $this->matchTypeForNullableUnionType($compatibleObjectType);
            if ($type instanceof ObjectType) {
                return new NullableType(new FullyQualified($type->getClassName()));
            }
        }

        if (! $compatibleObjectType instanceof ObjectType) {
            return null;
        }

        return new FullyQualified($compatibleObjectType->getClassName());
    }

    private function matchPhpParserUnionType(UnionType $unionType, TypeKind $typeKind): ?PhpParserUnionType
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

        $phpParserUnionedTypes = [];

        foreach ($unionType->getTypes() as $unionedType) {
            // void type is not allowed in union
            if ($unionedType instanceof VoidType) {
                return null;
            }

            /** @var Identifier|Name|null $phpParserNode */
            $phpParserNode = $this->phpStanStaticTypeMapper->mapToPhpParserNode($unionedType, $typeKind);
            if ($phpParserNode === null) {
                return null;
            }

            $phpParserUnionedTypes[] = $phpParserNode;
        }

        $phpParserUnionedTypes = array_unique($phpParserUnionedTypes);
        return new PhpParserUnionType($phpParserUnionedTypes);
    }

    private function resolveCompatibleObjectCandidate(UnionType $unionType): UnionType|TypeWithClassName|null
    {
        if ($this->doctrineTypeAnalyzer->isDoctrineCollectionWithIterableUnionType($unionType)) {
            $objectType = new ObjectType('Doctrine\Common\Collections\Collection');

            return $this->unionTypeAnalyzer->isNullable($unionType)
                ? new UnionType([new NullType(), $objectType])
                : $objectType;
        }

        $typesWithClassNames = $this->unionTypeAnalyzer->matchExclusiveTypesWithClassNames($unionType);
        if ($typesWithClassNames === []) {
            return null;
        }

        $sharedTypeWithClassName = $this->matchTwoObjectTypes($typesWithClassNames);
        if ($sharedTypeWithClassName instanceof TypeWithClassName) {
            return $this->correctObjectType($sharedTypeWithClassName);
        }

        // find least common denominator
        return $this->unionTypeCommonTypeNarrower->narrowToSharedObjectType($unionType);
    }

    /**
     * @param TypeWithClassName[] $typesWithClassNames
     */
    private function matchTwoObjectTypes(array $typesWithClassNames): ?TypeWithClassName
    {
        foreach ($typesWithClassNames as $typeWithClassName) {
            foreach ($typesWithClassNames as $nestedTypeWithClassName) {
                if (! $this->areTypeWithClassNamesRelated($typeWithClassName, $nestedTypeWithClassName)) {
                    continue 2;
                }
            }

            return $typeWithClassName;
        }

        return null;
    }

    private function areTypeWithClassNamesRelated(TypeWithClassName $firstType, TypeWithClassName $secondType): bool
    {
        if ($firstType->accepts($secondType, false)->yes()) {
            return true;
        }

        return $secondType->accepts($firstType, false)
            ->yes();
    }

    private function correctObjectType(TypeWithClassName $typeWithClassName): TypeWithClassName
    {
        if ($typeWithClassName->getClassName() === NodeAbstract::class) {
            return new ObjectType('PhpParser\Node');
        }

        if ($typeWithClassName->getClassName() === AbstractRector::class) {
            return new ObjectType('Rector\Core\Contract\Rector\RectorInterface');
        }

        return $typeWithClassName;
    }

    private function isFalseBoolUnion(UnionType $unionType): bool
    {
        if (count($unionType->getTypes()) !== 2) {
            return false;
        }

        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType instanceof ConstantBooleanType) {
                continue;
            }

            return false;
        }

        return true;
    }
}
