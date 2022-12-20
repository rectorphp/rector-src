<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeTypeAnalyzer;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\TypeAnalyzer\UnionTypeAnalyzer;

final class PropertyTypeDecorator
{
    public function __construct(
        private readonly UnionTypeAnalyzer $unionTypeAnalyzer,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly NodeFactory $nodeFactory,
    ) {
    }

    public function decoratePropertyUnionType(
        UnionType $unionType,
        Name|ComplexType|Identifier $typeNode,
        Property $property,
        PhpDocInfo $phpDocInfo,
        bool $changeVarTypeFallback = true
    ): void {
        if (! $this->unionTypeAnalyzer->isNullable($unionType)) {
            if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::UNION_TYPES)) {
                $property->type = $typeNode;
                return;
            }

            if ($changeVarTypeFallback) {
                $this->phpDocTypeChanger->changeVarType($phpDocInfo, $unionType);
            }

            return;
        }

        $property->type = $typeNode;

        $propertyProperty = $property->props[0];

        // add null default
        if ($propertyProperty->default === null) {
            $propertyProperty->default = $this->nodeFactory->createNull();
        }

        // has array with defined type? add docs
        if (! $this->isDocBlockRequired($unionType)) {
            return;
        }

        if (! $changeVarTypeFallback) {
            return;
        }

        $this->phpDocTypeChanger->changeVarType($phpDocInfo, $unionType);
    }

    private function isDocBlockRequired(UnionType $unionType): bool
    {
        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType->isArray()->yes()) {
                $describedArray = $unionedType->describe(VerbosityLevel::value());
                if ($describedArray !== 'array') {
                    return true;
                }
            }
        }

        return false;
    }
}
