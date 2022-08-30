<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

final class SkipUsedKey
{
    public function run()
    {
        $items = [];
        foreach ($items as $key => $value) {
            $result = $key;
        }
    }
}
