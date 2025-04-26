<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

final class SkipReassignInForeach {
    public function preparedValueForeach(): bool {
        $test = true;
        foreach([true, false] as $item) {
        	if(!$item) $test = false;
        }

        if(rand(0, 1)) $test = false;

        return $test;
    }
}
