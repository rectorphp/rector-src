<?php

namespace Rector\Tests\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector\Fixture;

final class SkipMethodCallInConditionWithConcat
{
    public function saveMyEntity($a, $entity): bool
    {
        if ($a . $entity->save()) {
            return true;
        }

        return true;
    }
}
