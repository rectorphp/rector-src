<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Source;

interface SomeInterfaceWithConstruct
{
    public function __construct(string $a, string $b);
}