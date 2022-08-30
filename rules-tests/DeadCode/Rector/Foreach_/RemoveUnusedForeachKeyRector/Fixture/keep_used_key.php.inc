<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

final class KeepUsedKey
{
    public function run()
    {
        $items = [];
        foreach ($items as $key => $value) {
            $result = $key;
        }
    }
}
