<?php

namespace Rector\Tests\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector\Fixture;

class SkipMethodCallInArray
{
    public function run()
    {
        if ([$this->execute()] === []) {
            return 'yes';
        }

        return 'yes';
    }
}
