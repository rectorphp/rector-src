<?php

namespace Rector\Tests\Php71\Rector\BooleanOr\IsIterableRector\Fixture;

class SkipMethodCall
{
    public function go($foo)
    {
        is_array($foo->execute()) || $foo->execute() instanceof \Traversable;
    }
}
