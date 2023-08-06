<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfNullableReturnRector\Fixture;

final class SkipDocBlockTypes
{
    public function run()
    {
        /** @var null|\stdClass $value */
        $value = $this->foo->bar();
        if (! $value instanceof \stdClass) {
            return null;
        }

        return $value;
    }
}
