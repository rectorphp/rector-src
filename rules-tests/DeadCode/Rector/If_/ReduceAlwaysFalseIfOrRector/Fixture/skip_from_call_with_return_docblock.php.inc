<?php

namespace Rector\Tests\DeadCode\Rector\If_\ReduceAlwaysFalseIfOrRector\Fixture;

class SkipFromCallWithReturnDocblock
{
    /**
     * @return \DateTime
     */
    private function get()
    {
        return null;
    }

    public function run($number)
    {
        if (! $this->get() || $number > 50) {
            return 'yes';
        }

        return 'no';
    }
}
