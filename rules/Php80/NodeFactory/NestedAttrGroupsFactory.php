<?php

declare(strict_types=1);

namespace Rector\Php80\NodeFactory;

use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Stmt\Use_;
use Rector\Php80\ValueObject\NestedDoctrineTagAndAnnotationToAttribute;
use Rector\PhpAttribute\NodeFactory\PhpNestedAttributeGroupFactory;
use Rector\PhpParser\Comparing\NodeComparator;

final readonly class NestedAttrGroupsFactory
{
    public function __construct(
        private PhpNestedAttributeGroupFactory $phpNestedAttributeGroupFactory,
        private NodeComparator $nodeComparator
    ) {
    }

    /**
     * @param NestedDoctrineTagAndAnnotationToAttribute[] $nestedDoctrineTagAndAnnotationToAttributes
     * @param Use_[] $uses
     * @return AttributeGroup[]
     */
    public function create(array $nestedDoctrineTagAndAnnotationToAttributes, array $uses): array
    {
        $attributeGroups = [];

        foreach ($nestedDoctrineTagAndAnnotationToAttributes as $nestedDoctrineTagAndAnnotationToAttribute) {
            $doctrineAnnotationTagValueNode = $nestedDoctrineTagAndAnnotationToAttribute->getDoctrineAnnotationTagValueNode();

            $nestedAnnotationToAttribute = $nestedDoctrineTagAndAnnotationToAttribute->getNestedAnnotationToAttribute();

            // do not create alternative for the annotation, only unwrap
            if (! $nestedAnnotationToAttribute->shouldRemoveOriginal()) {
                // add attributes
                $attributeGroup = $this->phpNestedAttributeGroupFactory->create(
                    $doctrineAnnotationTagValueNode,
                    $nestedDoctrineTagAndAnnotationToAttribute->getNestedAnnotationToAttribute(),
                    $uses
                );

                $lastAttributeGroup = end($attributeGroups);
                if ($lastAttributeGroup instanceof AttributeGroup && $this->nodeComparator->areNodesEqual($lastAttributeGroup, $attributeGroup)) {
                    continue;
                }

                $attributeGroups[] = $attributeGroup;
            }

            $nestedAttributeGroups = $this->phpNestedAttributeGroupFactory->createNested(
                $doctrineAnnotationTagValueNode,
                $nestedDoctrineTagAndAnnotationToAttribute->getNestedAnnotationToAttribute(),
            );

            $attributeGroups = [...$attributeGroups, ...$nestedAttributeGroups];
        }

        return $attributeGroups;
    }
}
