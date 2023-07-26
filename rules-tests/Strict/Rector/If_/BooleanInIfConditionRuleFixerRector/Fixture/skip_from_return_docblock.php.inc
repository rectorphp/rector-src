<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector\Fixture;

final class SkipFromReturnDocblock
{
    /**
     * @return array
     */
    private function getItems()
    {
    }

    public function run()
    {
        if ($this->getItems()) {
            return true;
        }

        return false;
    }
}
