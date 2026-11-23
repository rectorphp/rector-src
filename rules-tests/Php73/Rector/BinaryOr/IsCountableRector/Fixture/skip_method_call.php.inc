<?php

namespace Rector\Tests\Php73\Rector\BinaryOr\IsCountableRector\Fixture;

class SkipMethodCall
{
    public function go($foo)
    {
        is_array($foo->execute()) || $foo->execute() instanceof \Countable;
    }
}
