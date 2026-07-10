<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipWiderPropertyType
{
    public private(set) ?object $entity = null;

    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }

    public function clear(): void
    {
        $this->entity = null;
    }
}
