<?php

namespace Rector\Tests\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector\Fixture;

use RuntimeException;

final class SkipUsedInFinally
{
    public function run($name)
    {
        try {
            $res = hw_get($name);
        } catch (\RangeException $e) {
            // Nothing found - all ok'
        } finally {
            if (!isset($e)) {
                throw new RuntimeException("Component named '$name' already exists", -1, $module, $action);
            }
        }
    }
}
