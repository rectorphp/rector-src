<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc;

use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\DeadCode\PhpDoc\Guard\TemplateTypeRemovalGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class DeadVarTagValueNodeAnalyzer
{
    public function __construct(
        private TypeComparator $typeComparator,
        private StaticTypeMapper $staticTypeMapper,
        private TemplateTypeRemovalGuard $templateTypeRemovalGuard
    ) {
    }

    public function isDead(VarTagValueNode $varTagValueNode, Property $property): bool
    {
        if ($property->type === null) {
            return false;
        }

        if ($varTagValueNode->description !== '') {
            return false;
        }

        // is strict type superior to doc type? keep strict type only
        $propertyType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($property->type);
        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($varTagValueNode->type, $property);

        if (! $this->templateTypeRemovalGuard->isLegal($docType)) {
            return false;
        }

        if ($propertyType instanceof UnionType && ! $docType instanceof UnionType) {
            return ! $docType instanceof IntersectionType;
        }

        if ($propertyType instanceof ObjectType && $docType instanceof ObjectType) {
            // more specific type is already in the property
            return $docType->isSuperTypeOf($propertyType)
                ->yes();
        }

        if ($this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual(
            $property->type,
            $varTagValueNode->type,
            $property
        )) {
            return true;
        }

        return $docType instanceof UnionType && $this->typeComparator->areTypesEqual(
            TypeCombinator::removeNull($docType),
            $propertyType
        );
    }
}
