<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class PhpParserTypeAnalyzer
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function isCovariantSubtypeOf(
        Name | NullableType | UnionType | Identifier | IntersectionType $possibleSubtype,
        Name | NullableType | UnionType | Identifier | ComplexType $possibleParentType
    ): bool {
        // skip until PHP 8 is out
        if ($this->isUnionType($possibleSubtype, $possibleParentType)) {
            return false;
        }

        // possible - https://3v4l.org/ZuJCh
        if ($possibleSubtype instanceof NullableType && ! $possibleParentType instanceof NullableType) {
            return $this->isCovariantSubtypeOf($possibleSubtype->type, $possibleParentType);
        }

        // not possible - https://3v4l.org/iNDTc
        if (! $possibleSubtype instanceof NullableType && $possibleParentType instanceof NullableType) {
            return false;
        }

        $subtypeType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($possibleParentType);
        $parentType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($possibleSubtype);

        return $parentType->isSuperTypeOf($subtypeType)
            ->yes();
    }

    private function isUnionType(
        Identifier|Name|NullableType|UnionType|IntersectionType $possibleSubtype,
        ComplexType|Identifier|Name $possibleParentType
    ): bool {
        if ($possibleSubtype instanceof UnionType) {
            return true;
        }

        return $possibleParentType instanceof UnionType;
    }
}
