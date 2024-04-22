<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class SkipPhpDocTyped
{
    /** @param array{key: int} $arr */
    public function run($arr = [])
    {
        echo "hello ". (int) $arr['key'];
    }
}

?>
