<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

use Ds\Map;

class SkipDsMapGet
{
    public function foo(): void {
        $map = new Map();
        $value = $map->get('foo', null);
        if ($value) {
            //...
        }
    }
}
