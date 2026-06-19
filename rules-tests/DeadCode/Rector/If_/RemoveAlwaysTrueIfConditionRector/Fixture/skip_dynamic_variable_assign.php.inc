<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipDynamicVariableAssign
{
    public function build(array $params): string
    {
        $addHelpMessage = true;

        foreach (['addHelpMessage'] as $f) {
            if (isset($params[$f])) {
                ${$f} = (bool) $params[$f];
            }
        }

        if ($addHelpMessage) {
            return 'help';
        }

        return 'no help';
    }
}
