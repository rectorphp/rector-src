<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

use Rector\Contract\Rector\RectorInterface;

#[\Attribute]
final class SkipAttributeMarker
{
    /**
     * @param RectorInterface $someClass
     */
    public function __construct(string $someClass)
    {
    }
}
