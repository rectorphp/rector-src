<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector\Fixture;

class SkipInArg
{
    public function run($a)
    {
        $a
            && $this->execute(
                    $a instanceof stdClass ? 'a' : 'b',
                    $a instanceof stdClass ? 'a' : 'b'
                );
    }

    private function execute(...$args) {}
}
