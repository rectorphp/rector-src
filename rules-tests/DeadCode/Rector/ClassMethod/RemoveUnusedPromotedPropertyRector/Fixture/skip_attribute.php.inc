<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

#[\Attribute]
final class SkipAttribute
{
    public function __construct(
        private $someUnusedDependency,
    ) {
    }
}
