<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Source\TraitConsumer;

class SkipUsedByTrait2
{
    use TraitConsumer;

    private static function hello()
    {
    }
}
