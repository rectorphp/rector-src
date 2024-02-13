<?php

declare(strict_types=1);

namespace Rector\Php80\NodeFactory;

use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Stmt\Use_;
use Rector\Php80\ValueObject\NestedDoctrineTagAndAnnotationToAttribute;
use Rector\PhpAttribute\NodeFactory\PhpNestedAttributeGroupFactory;

final readonly class NestedAttrGroupsFactory
{
    public function __construct(
        private PhpNestedAttributeGroupFactory $phpNestedAttributeGroupFactory
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
                $attributeGroups[] = $this->phpNestedAttributeGroupFactory->create(
                    $doctrineAnnotationTagValueNode,
                    $nestedDoctrineTagAndAnnotationToAttribute->getNestedAnnotationToAttribute(),
                    $uses
                );
            }

            $nestedAttributeGroups = $this->phpNestedAttributeGroupFactory->createNested(
                $doctrineAnnotationTagValueNode,
                $nestedDoctrineTagAndAnnotationToAttribute->getNestedAnnotationToAttribute(),
            );

            $attributeGroups = [...$attributeGroups, ...$nestedAttributeGroups];
        }

        return array_unique($attributeGroups, SORT_REGULAR);
    }
}
