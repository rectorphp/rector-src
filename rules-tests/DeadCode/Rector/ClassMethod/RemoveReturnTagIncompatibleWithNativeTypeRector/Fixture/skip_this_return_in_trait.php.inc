<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Source\RouteInterface;

trait SkipThisReturnInTrait
{
    /**
     * @return $this
     */
    public function withVerbs(): RouteInterface
    {
        return clone $this;
    }
}
