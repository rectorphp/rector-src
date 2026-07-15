<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipWiderPropertyType
{
    private(set) ?object $entity;

    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }

    public function clear(): void
    {
        $this->entity = null;
    }
}
