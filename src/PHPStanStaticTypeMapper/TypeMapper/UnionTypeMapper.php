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
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
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
        $unionTypesNodes = [];
        foreach ($type->getTypes() as $unionedType) {
            $unionTypesNodes[] = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($unionedType);
        }

        return new BracketsAwareUnionTypeNode($unionTypesNodes);
    }

    /**
     * @param UnionType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        $phpParserUnionType = $this->matchPhpParserUnionType($type, $typeKind);
        if ($phpParserUnionType instanceof PhpParserUnionType) {
            return $this->resolveUnionTypeNode($phpParserUnionType);
        }

        return $phpParserUnionType;
    }

    /**
     * If type is nullable, and has only one other value,
     * this creates at least "?Type" in case of PHP 7.1-7.4
     */
    private function resolveTypeWithNullablePHPParserUnionType(
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
            // ?false is allowed only since PHP 8.2+, lets fallback to bool instead
            if ($type->toString() === 'false' && ! $this->phpVersionProvider->isAtLeastPhpVersion(
                PhpVersionFeature::NULL_FALSE_TRUE_STANDALONE_TYPE
            )) {
                return new NullableType(new Identifier('bool'));
            }

            return $nullableType;
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

        $types = [$type];
        $types[] = new Identifier('null');

        return new PhpParserUnionType($types);
    }

    private function resolveUnionTypes(PhpParserUnionType $phpParserUnionType): ?PhpParserUnionType
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

        return $phpParserUnionType;
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
     * @return Name|FullyQualified|ComplexType|Identifier|null
     */
    private function matchPhpParserUnionType(UnionType $unionType, string $typeKind): ?Node
    {
        $phpParserUnionedTypes = [];

        foreach ($unionType->getTypes() as $unionedType) {
            // NullType or ConstantBooleanType with false value inside UnionType is allowed
            // void type and mixed type are not allowed in union
            $phpParserNode = $this->phpStanStaticTypeMapper->mapToPhpParserNode($unionedType, TypeKind::UNION);
            if ($phpParserNode === null) {
                return null;
            }

            // special callable type only not allowed on property
            if ($typeKind === TypeKind::PROPERTY && $unionedType->isCallable()->yes()) {
                return null;
            }

            $phpParserUnionedTypes[] = $phpParserNode;
        }

        /** @var Identifier[]|Name[] $phpParserUnionedTypes */
        $phpParserUnionedTypes = array_unique($phpParserUnionedTypes, SORT_REGULAR);

        $countPhpParserUnionedTypes = count($phpParserUnionedTypes);
        if ($countPhpParserUnionedTypes === 1) {
            return $phpParserUnionedTypes[0];
        }

        return $this->resolveTypeWithNullablePHPParserUnionType(new PhpParserUnionType($phpParserUnionedTypes));
    }

    private function resolveUnionTypeNode(PhpParserUnionType $phpParserUnionType): ?PhpParserUnionType
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
            return null;
        }

        // special case that would crash, when stdClass and object is used,
        if ($this->hasObjectAndStaticType($phpParserUnionType)) {
            return null;
        }

        return $phpParserUnionType;
    }
}
