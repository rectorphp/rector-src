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
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\UnionTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\ValueObject\UnionTypeAnalysis;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @implements TypeMapperInterface<UnionType>
 */
final class UnionTypeMapper implements TypeMapperInterface
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly UnionTypeAnalyzer $unionTypeAnalyzer,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function autowire(PHPStanStaticTypeMapper $phpStanStaticTypeMapper): void
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
    }

    public function getNodeClass(): string
    {
        return UnionType::class;
    }

    /**
     * @param UnionType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        // note: cannot be handled by PHPStan as uses no-space around |
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
        // special case for nullable
        $nullabledType = $this->matchTypeForNullableUnionType($type);
        if (! $nullabledType instanceof Type) {
            return $this->matchTypeForUnionedTypes($type, $typeKind);
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
                return $this->resolveUnionTypes($phpParserUnionType);
            }

            $firstTypeValue = $firstType->toString();
            $secondTypeValue = $secondType->toString();

            if ($firstTypeValue === $secondTypeValue) {
                return $this->resolveUnionTypes($phpParserUnionType);
            }

            if ($firstTypeValue === 'null') {
                return $this->resolveNullableType(new NullableType($secondType));
            }

            if ($secondTypeValue === 'null') {
                return $this->resolveNullableType(new NullableType($firstType));
            }
        }

        return $this->resolveUnionTypes($phpParserUnionType);
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
        $unionTypeAnalysis = $this->unionTypeAnalyzer->analyseForArrayAndIterable($unionType);
        if (! $unionTypeAnalysis instanceof UnionTypeAnalysis) {
            return false;
        }

        if (! $unionTypeAnalysis->hasIterable()) {
            return false;
        }

        return $unionTypeAnalysis->hasArray();
    }

    private function resolveUnionTypes(PhpParserUnionType $phpParserUnionType): ?PhpParserUnionType
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

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
        $hasAnonymousObjectType = false;
        $hasObjectType = false;
        foreach ($phpParserUnionType->types as $type) {
            if ($type instanceof Identifier && $type->toString() === 'object') {
                $hasAnonymousObjectType = true;
                continue;
            }

            if ($type instanceof FullyQualified || ($type instanceof Name && $type->isSpecialClassName())) {
                $hasObjectType = true;
                continue;
            }
        }

        return $hasObjectType && $hasAnonymousObjectType;
    }

    /**
     * @param TypeKind::* $typeKind
     * @return Name|FullyQualified|ComplexType|Identifier|null
     */
    private function matchTypeForUnionedTypes(UnionType $unionType, string $typeKind): ?Node
    {
        // use first unioned type in case of unioned object types
        $compatibleObjectTypeNode = $this->processResolveCompatibleObjectCandidates($unionType);
        if ($compatibleObjectTypeNode instanceof NullableType || $compatibleObjectTypeNode instanceof FullyQualified) {
            return $compatibleObjectTypeNode;
        }

        $phpParserUnionType = $this->matchPhpParserUnionType($unionType, $typeKind);
        if ($phpParserUnionType instanceof NullableType) {
            return $phpParserUnionType;
        }

        if ($phpParserUnionType instanceof PhpParserUnionType) {
            return $this->resolveUnionTypeNode($phpParserUnionType);
        }

        return $phpParserUnionType;
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
     * @return Name|FullyQualified|ComplexType|Identifier|null
     */
    private function matchPhpParserUnionType(UnionType $unionType, string $typeKind): ?Node
    {
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
        if ($countPhpParserUnionedTypes === 1) {
            return $phpParserUnionedTypes[0];
        }

        if ($countPhpParserUnionedTypes === 0) {
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
        $typesWithClassNames = $this->unionTypeAnalyzer->matchExclusiveTypesWithClassNames($unionType);
        if ($typesWithClassNames === []) {
            return null;
        }

        $sharedTypeWithClassName = $this->matchTwoObjectTypes($typesWithClassNames);
        if ($sharedTypeWithClassName instanceof TypeWithClassName) {
            return $this->correctObjectType($sharedTypeWithClassName);
        }

        return null;
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
            return new ObjectType('Rector\Contract\Rector\RectorInterface');
        }

        return $typeWithClassName;
    }

    private function resolveUnionTypeNode(PhpParserUnionType $phpParserUnionType): ?PhpParserUnionType
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

        if ($this->hasObjectAndStaticType($phpParserUnionType)) {
            return null;
        }

        return $phpParserUnionType;
    }
}
