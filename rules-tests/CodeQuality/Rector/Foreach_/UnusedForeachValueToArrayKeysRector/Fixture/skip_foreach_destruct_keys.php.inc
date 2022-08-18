<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector\Fixture;

final class SkipForeachDestructKeys
{
    public function run(array $definitions)
    {
        foreach ($definitions as $id => [$domElement, $file]) {
            if ($file) {
                return $file;
            }
        }
    }
}
