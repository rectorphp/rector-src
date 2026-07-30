<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeCacheProvider;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\UntypedPropertyParent;

final class SkipUntypedParentProperty extends UntypedPropertyParent
{
    /**
     * @var SomeCacheProvider|null
     */
    protected $model;

    public function __construct(SomeCacheProvider $model)
    {
        $this->model = $model;
    }
}
