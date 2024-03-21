<?php

namespace Rector\Tests\DeadCode\Rector\If_\ReduceAlwaysFalseIfOrRector\Fixture;

class SkipFromDocblock
{
    /**
     * @param int $number
     */
    public function run($number)
    {
        if (! is_int($number) || $number > 50) {
            return 'yes';
        }

        return 'no';
    }
}
