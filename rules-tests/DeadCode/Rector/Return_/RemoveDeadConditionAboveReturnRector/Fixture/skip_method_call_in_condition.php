<?php

namespace Rector\Tests\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector\Fixture;

final class SkipMethodCallInCondition
{
    public function saveMyEntity($entity): bool
    {
        if ($entity->save()) {
            return true;
        }

        return true;
    }
}
