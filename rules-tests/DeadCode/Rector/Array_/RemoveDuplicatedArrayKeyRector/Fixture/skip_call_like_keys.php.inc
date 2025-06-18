<?php

namespace Rector\Tests\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector\Fixture;

class SkipMethodAndFunctCalls
{
    public function lists()
    {
        $items = [
            rand() => 'A',
            rand() => 'A',
        ];
    }
}

?>
