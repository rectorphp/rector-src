<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Source\TraitConsumer;

class SkipUsedByTraitClosureTrait
{
    use TraitConsumer;

    private function world()
    {
    }
}
