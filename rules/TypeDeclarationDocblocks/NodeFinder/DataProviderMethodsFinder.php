<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\NodeFinder;

use PhpParser\Node\Attribute;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\TypeDeclaration\ValueObject\DataProviderNodes;
use Rector\TypeDeclarationDocblocks\Enum\TestClassName;

/**
 * @todo re-use in rector-phpunit
 * @see AddParamTypeBasedOnPHPUnitDataProviderRector
 */
final readonly class DataProviderMethodsFinder
{
    public function __construct(
        private PhpDocInfoFactory $phpDocInfoFactory,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function findDataProviderNodes(ClassMethod $classMethod): DataProviderNodes
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($classMethod);
        if ($phpDocInfo instanceof PhpDocInfo) {
            $phpdocNodes = $phpDocInfo->getTagsByName('@dataProvider');
        } else {
            $phpdocNodes = [];
        }

        $attributes = $this->findDataProviderAttributes($classMethod);

        return new DataProviderNodes([...$attributes, ...$phpdocNodes]);
    }

    /**
     * @return array<Attribute>
     */
    private function findDataProviderAttributes(ClassMethod $classMethod): array
    {
        $dataProviders = [];

        foreach ($classMethod->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if (! $this->nodeNameResolver->isName($attribute->name, TestClassName::DATA_PROVIDER)) {
                    continue;
                }

                $dataProviders[] = $attribute;
            }
        }

        return $dataProviders;
    }
}
