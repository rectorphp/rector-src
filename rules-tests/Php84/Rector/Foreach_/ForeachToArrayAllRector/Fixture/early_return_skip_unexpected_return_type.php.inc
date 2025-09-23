<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class EarlyReturnSkipUnexpectedReturnType
{
    public function run(array $animals)
    {
        foreach ($animals as $animal) {
            if (! str_starts_with($animal, 'c')) {
                return 'ok';
            }
        }
        return true;
    }
}
?>
