<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class SkipOuterSpreadNestedCall
{
    public function flatten(array $groups): array
    {
        return array_merge(...array_values($groups));
    }
}
