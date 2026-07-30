<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source;

abstract class UntypedPropertyParent
{
    /**
     * @var SomeCacheProvider|null
     */
    protected $model;
}
