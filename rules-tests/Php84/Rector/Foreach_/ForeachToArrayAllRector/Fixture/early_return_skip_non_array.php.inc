<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class EarlyReturnSkipNonArray
{
    public function run(\ArrayIterator $animals)
    {
        foreach ($animals as $animal) {
            if (! str_starts_with($animal, 'c')) {
                echo 'ok';
                return false;
            }
        }
        return true;
    }
}
?>
