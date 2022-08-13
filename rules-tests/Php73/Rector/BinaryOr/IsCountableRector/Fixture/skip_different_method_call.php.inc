<?php

namespace Rector\Tests\Php73\Rector\BinaryOr\IsCountableRector\Fixture;

class SkipDifferentMethodCall
{
    public function go($foo)
    {
        is_array($foo->execute()) || $foo->run() instanceof \Countable;
    }
}
