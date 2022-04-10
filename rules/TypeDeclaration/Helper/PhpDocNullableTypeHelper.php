<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Helper;

use function count;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Param;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\TypeAnalyzer\UnionTypeAnalyzer;

class PhpDocNullableTypeHelper
{
    private UnionTypeAnalyzer $unionTypeAnalyzer;

    private StaticTypeMapper $staticTypeMapper;

    private ValueResolver $valueResolver;

    public function __construct(
        UnionTypeAnalyzer $unionTypeAnalyzer,
        StaticTypeMapper $staticTypeMapper,
        ValueResolver $valueResolver,
    ) {
        $this->unionTypeAnalyzer = $unionTypeAnalyzer;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return \PHPStan\Type\Type|null Returns null if it was not possible to resolve new php doc type or if update is not required
     */
    public function resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserType(
        Type $phpDocType,
        Type $phpParserType
    ): ?Type {
        return $this->resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserTypeNullInfo(
            $phpDocType,
            $this->isParserTypeContainingNullType($phpParserType)
        );
    }

    /**
     * @return \PHPStan\Type\Type|null Returns null if it was not possible to resolve new php doc param type or if update is not required
     */
    public function resolveUpdatedPhpDocTypeFromPhpDocTypeAndParamNode(Type $phpDocType, Param $param): ?Type
    {
        if ($param->type === null) {
            return null;
        }

        $phpParserType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);

        if ($phpParserType instanceof UnionType) {
            $isPhpParserTypeContainingNullType = $this->unionTypeAnalyzer->isUnionTypeContainingNullType(
                $phpParserType
            );
        } elseif ($param->default !== null) {
            $value = $this->valueResolver->getValue($param->default);
            $isPhpParserTypeContainingNullType = $value === null || ($param->default instanceof ConstFetch && $value === 'null');
        } else {
            $isPhpParserTypeContainingNullType = false;
        }

        return $this->resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserTypeNullInfo(
            $phpDocType,
            $isPhpParserTypeContainingNullType
        );
    }

    private function isItRequiredToRemoveOrAddNullTypeToUnion(
        bool $phpDocTypeContainsNullType,
        bool $phpParserTypeContainsNullType,
    ): bool {
        return ($phpParserTypeContainsNullType && ! $phpDocTypeContainsNullType) || (! $phpParserTypeContainsNullType && $phpDocTypeContainsNullType);
    }

    /**
     * @param \PHPStan\Type\Type[] $updatedDocTypes
     */
    private function composeUpdatedPhpDocType(array $updatedDocTypes): mixed
    {
        if (count($updatedDocTypes) === 1) {
            $updatedPhpDocType = $updatedDocTypes[0];
        } else {
            try {
                $updatedPhpDocType = new UnionType($updatedDocTypes);
            } catch (ShouldNotHappenException $exception) {
                return null;
            }
        }

        return $updatedPhpDocType;
    }

    private function isParserTypeContainingNullType(Type $phpParserType): bool
    {
        $phpParserTypeContainsNullType = false;

        if ($phpParserType instanceof UnionType) {
            $phpParserTypeContainsNullType = $this->unionTypeAnalyzer->isUnionTypeContainingNullType($phpParserType);
        }

        return $phpParserTypeContainsNullType;
    }

    /**
     * @return mixed|null
     */
    private function resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserTypeNullInfo(
        Type $phpDocType,
        bool $isPhpParserTypeContainingNullType
    ): mixed {
        /** @var array<\PHPStan\Type\NullType|\PHPStan\Type\UnionType> $updatedDocTypes */
        $updatedDocTypes = [];
        $phpDocTypeContainsNullType = false;
        if ($phpDocType instanceof UnionType) {
            $phpDocTypeContainsNullType = $this->unionTypeAnalyzer->isUnionTypeContainingNullType($phpDocType);
            foreach ($phpDocType->getTypes() as $subType) {
                if ($subType instanceof NullType) {
                    continue;
                }
                $updatedDocTypes[] = $subType;
            }
        } else {
            $updatedDocTypes[] = $phpDocType;
        }

        if (! $this->isItRequiredToRemoveOrAddNullTypeToUnion(
            $phpDocTypeContainsNullType,
            $isPhpParserTypeContainingNullType
        )) {
            return null;
        }

        if ($isPhpParserTypeContainingNullType) {
            $updatedDocTypes[] = new NullType();
        }

        return $this->composeUpdatedPhpDocType($updatedDocTypes);
    }
}
