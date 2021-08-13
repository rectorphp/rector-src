<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use stdClass;

class SkipUnionArrayWithoutType
{
    /**
     * @var array|stdClass[]
     */
    public $list = [];

    public function run()
    {
        foreach ($this->list as $key => $list) {
            if (! $list instanceof stdClass) {
                $this->list[$key] = new stdClass;
            }
        }
    }
}

?>
