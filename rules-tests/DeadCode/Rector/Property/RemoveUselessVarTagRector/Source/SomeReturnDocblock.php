<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source;

use DateTime;

class SomeReturnDocblock
{
    /**
     * @return DateTime
     */
    public function get()
    {
    }
}
