<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType as PHPParserNodeIntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType as PhpParserUnionType;
use PhpParser\NodeAbstract;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\BoolUnionTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\UnionTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\UnionTypeCommonTypeNarrower;
use Rector\PHPStanStaticTypeMapper\ValueObject\UnionTypeAnalysis;
use Symfony\Contracts\Service\Attribute\Required;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @implements TypeMapperInterface<UnionType>
 */
final class UnionTypeMapper implements TypeMapperInterface
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    public function __construct(
        private readonly DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly UnionTypeAnalyzer $unionTypeAnalyzer,
        private readonly BoolUnionTypeAnalyzer $boolUnionTypeAnalyzer,
        private readonly UnionTypeCommonTypeNarrower $unionTypeCommonTypeNarrower,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly TypeFactory $typeFactory
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
        return UnionType::class;
    }

    /**
     * @param UnionType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $unionTypesNodes = [];
        $skipIterable = $this->shouldSkipIterable($type);

        foreach ($type->getTypes() as $unionedType) {
            if ($unionedType instanceof IterableType && $skipIterable) {
                continue;
            }

            $unionTypesNodes[] = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($unionedType);
        }

        $unionTypesNodes = array_unique($unionTypesNodes);
        return new BracketsAwareUnionTypeNode($unionTypesNodes);
    }

    /**
     * @param UnionType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        $arrayNode = $this->matchArrayTypes($type);
        if ($arrayNode !== null) {
            return $arrayNode;
        }

        // special case for nullable
        $nullabledType = $this->matchTypeForNullableUnionType($type);
        if (! $nullabledType instanceof Type) {
            // use first unioned type in case of unioned object types
            return $this->matchTypeForUnionedObjectTypes($type, $typeKind);
        }

        return $this->mapNullabledType($nullabledType, $typeKind);
    }

    public function resolveTypeWithNullablePHPParserUnionType(
        PhpParserUnionType $phpParserUnionType
    ): PhpParserUnionType|NullableType|null {
        $totalTypes = count($phpParserUnionType->types);
        if ($totalTypes === 2) {
            $phpParserUnionType->types = array_values($phpParserUnionType->types);
            $firstType = $phpParserUnionType->types[0];
            $secondType = $phpParserUnionType->types[1];

            try {
                Assert::isAnyOf($firstType, [Name::class, Identifier::class]);
                Assert::isAnyOf($secondType, [Name::class, Identifier::class]);
            } catch (InvalidArgumentException) {
                return $this->resolveUnionTypes($phpParserUnionType, $totalTypes);
            }

            $firstTypeValue = $firstType->toString();
            $secondTypeValue = $secondType->toString();

            if ($firstTypeValue === $secondTypeValue) {
                return $this->resolveUnionTypes($phpParserUnionType, $totalTypes);
            }

            if ($firstTypeValue === 'null') {
                return $this->resolveNullableType(new NullableType($secondType));
            }

            if ($secondTypeValue === 'null') {
                return $this->resolveNullableType(new NullableType($firstType));
            }
        }

        return $this->resolveUnionTypes($phpParserUnionType, $totalTypes);
    }

    private function resolveNullableType(NullableType $nullableType): null|NullableType|PhpParserUnionType
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::NULLABLE_TYPE)) {
            return null;
        }

        /** @var PHPParserNodeIntersectionType|Identifier|Name $type */
        $type = $nullableType->type;
        if (! $type instanceof PHPParserNodeIntersectionType) {
            return $nullableType;
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::INTERSECTION_TYPES)) {
            return null;
        }

        $types = [$type];
        $types[] = new Identifier('null');

        return new PhpParserUnionType($types);
    }

    /**
     * @param TypeKind::* $typeKind
     */
    private function mapNullabledType(Type $nullabledType, string $typeKind): ?Node
    {
        // void cannot be nullable
        if ($nullabledType->isVoid()->yes()) {
            return null;
        }

        $nullabledTypeNode = $this->phpStanStaticTypeMapper->mapToPhpParserNode($nullabledType, $typeKind);
        if (! $nullabledTypeNode instanceof Node) {
            return null;
        }

        if (in_array($nullabledTypeNode::class, [NullableType::class, ComplexType::class], true)) {
            return $nullabledTypeNode;
        }

        /** @var Name $nullabledTypeNode */
        if (! $this->nodeNameResolver->isNames($nullabledTypeNode, ['false', 'mixed'])) {
            return $this->resolveNullableType(new NullableType($nullabledTypeNode));
        }

        return null;
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

    private function matchArrayTypes(UnionType $unionType): Identifier | NullableType | PhpParserUnionType | null
    {
        $unionTypeAnalysis = $this->unionTypeAnalyzer->analyseForNullableAndIterable($unionType);
        if (! $unionTypeAnalysis instanceof UnionTypeAnalysis) {
            return null;
        }

        $type = $unionTypeAnalysis->hasIterable() ? 'iterable' : 'array';
        if ($unionTypeAnalysis->isNullableType()) {
            return $this->resolveNullableType(new NullableType($type));
        }

        return new Identifier($type);
    }

    private function resolveUnionTypes(PhpParserUnionType $phpParserUnionType, int $totalTypes): ?PhpParserUnionType
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

        if ($totalTypes === 2) {
            return $phpParserUnionType;
        }

        $identifierNames = [];
        foreach ($phpParserUnionType->types as $type) {
            if ($type instanceof Identifier) {
                $identifierNames[] = $type->toString();
            }
        }

        if (! in_array('bool', $identifierNames, true)) {
            return $phpParserUnionType;
        }

        if (! in_array('false', $identifierNames, true)) {
            return $phpParserUnionType;
        }

        $phpParserUnionType->types = array_filter(
            $phpParserUnionType->types,
            static fn (Node $node): bool => ! $node instanceof Identifier || $node->toString() !== 'false'
        );
        $phpParserUnionType->types = array_values($phpParserUnionType->types);

        return $phpParserUnionType;
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
        $diff = array_diff(['object', ObjectReference::STATIC], $typeNames);

        return $diff === [];
    }

    /**
     * @param TypeKind::* $typeKind
     * @return Name|FullyQualified|ComplexType|Identifier|null
     */
    private function matchTypeForUnionedObjectTypes(UnionType $unionType, string $typeKind): ?Node
    {
        $phpParserUnionType = $this->matchPhpParserUnionType($unionType, $typeKind);

        if ($phpParserUnionType instanceof NullableType) {
            return $phpParserUnionType;
        }

        if ($phpParserUnionType instanceof PhpParserUnionType) {
            return $this->narrowBoolType($unionType, $phpParserUnionType, $typeKind);
        }

        $compatibleObjectTypeNode = $this->processResolveCompatibleObjectCandidates($unionType);
        if ($compatibleObjectTypeNode instanceof NullableType || $compatibleObjectTypeNode instanceof FullyQualified) {
            return $compatibleObjectTypeNode;
        }

        $integerIdentifier = $this->narrowIntegerType($unionType);
        if ($integerIdentifier instanceof Identifier) {
            return $integerIdentifier;
        }

        return $this->narrowStringTypes($unionType);
    }

    private function narrowStringTypes(UnionType $unionType): ?Identifier
    {
        foreach ($unionType->getTypes() as $unionedType) {
            if (! $unionedType->isString()->yes()) {
                return null;
            }
        }

        return new Identifier('string');
    }

    private function processResolveCompatibleObjectCandidates(UnionType $unionType): ?Node
    {
        // the type should be compatible with all other types, e.g. A extends B, B
        $compatibleObjectType = $this->resolveCompatibleObjectCandidate($unionType);
        if ($compatibleObjectType instanceof UnionType) {
            $type = $this->matchTypeForNullableUnionType($compatibleObjectType);
            if ($type instanceof ObjectType) {
                return $this->resolveNullableType(new NullableType(new FullyQualified($type->getClassName())));
            }
        }

        if (! $compatibleObjectType instanceof ObjectType) {
            return null;
        }

        return new FullyQualified($compatibleObjectType->getClassName());
    }

    /**
     * @param TypeKind::* $typeKind
     */
    private function matchPhpParserUnionType(
        UnionType $unionType,
        string $typeKind
    ): PhpParserUnionType|NullableType|null {
        $phpParserUnionedTypes = [];

        foreach ($unionType->getTypes() as $unionedType) {
            // void type and mixed type are not allowed in union
            if (in_array($unionedType::class, [MixedType::class, VoidType::class], true)) {
                return null;
            }

            /**
             * NullType or ConstantBooleanType with false value inside UnionType is allowed
             */
            $phpParserNode = $this->resolveAllowedStandaloneTypeInUnionType($unionedType, $typeKind);
            if ($phpParserNode === null) {
                return null;
            }

            if ($phpParserNode instanceof PHPParserNodeIntersectionType && $unionedType instanceof IntersectionType) {
                return null;
            }

            $phpParserUnionedTypes[] = $phpParserNode;
        }

        /** @var Identifier[]|Name[] $phpParserUnionedTypes */
        $phpParserUnionedTypes = array_unique($phpParserUnionedTypes);

        $countPhpParserUnionedTypes = count($phpParserUnionedTypes);
        if ($countPhpParserUnionedTypes < 2) {
            return null;
        }

        return $this->resolveTypeWithNullablePHPParserUnionType(new PhpParserUnionType($phpParserUnionedTypes));
    }

    /**
     * @param TypeKind::* $typeKind
     */
    private function resolveAllowedStandaloneTypeInUnionType(
        Type $unionedType,
        string $typeKind
    ): Identifier|Name|null|PHPParserNodeIntersectionType|ComplexType {
        if ($unionedType instanceof NullType) {
            return new Identifier('null');
        }

        if ($unionedType instanceof ConstantBooleanType && ! $unionedType->getValue()) {
            return new Identifier('false');
        }

        return $this->phpStanStaticTypeMapper->mapToPhpParserNode($unionedType, $typeKind);
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
        return $firstType->accepts($secondType, false)
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

    private function narrowIntegerType(UnionType $unionType): ?Identifier
    {
        foreach ($unionType->getTypes() as $unionedType) {
            if (! $unionedType->isInteger()->yes()) {
                return null;
            }
        }

        return new Identifier('int');
    }

    /**
     * @param TypeKind::* $typeKind
     */
    private function narrowBoolType(
        UnionType $unionType,
        PhpParserUnionType $phpParserUnionType,
        string $typeKind
    ): PhpParserUnionType|null|Identifier|Name|ComplexType {
        // maybe all one type
        if ($this->boolUnionTypeAnalyzer->isBoolUnionType($unionType)) {
            return new Identifier('bool');
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

        if ($this->hasObjectAndStaticType($phpParserUnionType)) {
            return null;
        }

        $unionType = $this->typeFactory->createMixedPassedOrUnionType($unionType->getTypes());
        if (! $unionType instanceof UnionType) {
            return $this->phpStanStaticTypeMapper->mapToPhpParserNode($unionType, $typeKind);
        }

        return $phpParserUnionType;
    }
}
