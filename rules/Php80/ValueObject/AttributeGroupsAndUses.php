<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Stmt\Use_;

final class AttributeGroupsAndUses
{
    /**
     * @param AttributeGroup[] $attributeGroups
     * @param Use_[] $uses
     */
    public function __construct(
        private readonly array $attributeGroups,
        private readonly array $uses
    ) {
    }

    /**
     * @return AttributeGroup[]
     */
    public function getAttributeGroups(): array
    {
        return $this->attributeGroups;
    }

    /**
     * @return Use_[]
     */
    public function getUses(): array
    {
        return $this->uses;
    }
}
