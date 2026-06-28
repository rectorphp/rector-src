<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipInterfaceExistsInLoop
{
    /**
     * @param string[] $dependencies
     */
    public function check(array $dependencies): void
    {
        foreach ($dependencies as $dep) {
            if (! \class_exists($dep, true) && ! \interface_exists($dep, true)) {
                continue;
            }

            echo $dep;
        }
    }
}
