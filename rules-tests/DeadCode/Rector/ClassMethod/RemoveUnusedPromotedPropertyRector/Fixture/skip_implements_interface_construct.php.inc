<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Source\SomeInterfaceWithConstruct;

final class SkipImplementsInterfaceConstruct implements SomeInterfaceWithConstruct
{
    public function __construct(private string $a, private string $b)
    {
        echo $a;
    }
}
