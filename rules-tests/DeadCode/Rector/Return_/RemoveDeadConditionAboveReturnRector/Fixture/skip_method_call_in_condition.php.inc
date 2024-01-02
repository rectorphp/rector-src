<?php

namespace Rector\Tests\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector\Fixture;

final class SkipMethodCallInCondition
{
    public function saveMyEntity($Entity) {
        if ($Entity->save()) {
            return true;
        }

        return true;
    }
}
