<?php

namespace Rector\Tests\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector\Fixture;

class SkipMethodAndFunctCalls
{
    public function __get(string $name)
    {
        return rand();
    }

    public function lists()
    {
        $items = [
            $this->foo => 'A',
            $this->foo => 'A',
        ];
    }
}

?>
