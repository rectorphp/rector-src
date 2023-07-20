<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class SkipNegatedUnionTypeFromDoc
{
    /**
     * @param int|int[]|string $value
     */
    public function run($value)
    {
        return ! empty($value);
    }
}
