<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class EarlyReturnSkipMultipleStatements
{
    public function run(array $animals)
    {
        foreach ($animals as $animal) {
            if (! str_starts_with($animal, 'c')) {
                return false;
            }
        }
        echo 'ok';
        return true;
    }
}
?>
