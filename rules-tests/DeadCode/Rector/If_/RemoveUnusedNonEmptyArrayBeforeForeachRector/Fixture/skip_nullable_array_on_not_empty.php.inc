<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipNullableArrayOnNotEmpty
{
    public function getPrice()
    {
        $tierPrices = random_int(0, 1) ? [] : null;

        if (! empty($tierPrices)) {
            foreach ($tierPrices as $tierPrice) {
                if ($tierPrice >= 0) {
                    return $tierPrice;
                }
            }
        }

        return 0.0;
    }
}