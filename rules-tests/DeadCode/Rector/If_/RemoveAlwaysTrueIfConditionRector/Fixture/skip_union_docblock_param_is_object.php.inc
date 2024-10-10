<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

use DateTime;
use stdClass;

class SkipUnionDocblockParamIsObject
{
    /**
     * @param DateTime|stdClass|null $param
     */
    function bar($param)
    {
        if (! is_object($param)) {
            return '-';
        }

        if ($param instanceof Datetime) {
            return 'a';
        }

        if ($param instanceof stdClass) {
            return 'b';
        }

        return 'c';
    }
}
