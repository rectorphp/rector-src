<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

#[\Attribute()]
final class SkipAttributeMarker
{
    /**
     * @param class-string $className
     */
    public function __construct(string $className)
    {
    }
}
