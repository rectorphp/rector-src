<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class PropertyTypeDefaultValueAnalyzer
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function doesConflictWithDefaultValue(PropertyProperty $propertyProperty, Type $propertyType): bool
    {
        if (! $propertyProperty->default instanceof Expr) {
            return false;
        }

        // the defaults can be in conflict
        $defaultType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($propertyProperty->default);
        if ($defaultType->isArray()->yes() && $propertyType->isArray()->yes()) {
            return false;
        }

        // type is not matching, skip it
        return ! $defaultType->isSuperTypeOf($propertyType)
            ->yes();
    }
}
