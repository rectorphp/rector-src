<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use stdClass;

final class SkipPropertyFetch
{
    /** @var stdClass */
    private $var;

    public function init()
    {
        $this->var = new stdClass;
    }

    public function go()
    {
        if (! $this->var instanceof stdClass) {
            echo 'you need to run init() first' . PHP_EOL;
            return;
        }

        echo 'success' . PHP_EOL;
    }
}
