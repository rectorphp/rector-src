<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipClassExistsAfterEval
{
    public function compile(string $class, string $compiled): object
    {
        if (! \class_exists($class)) {
            // the class is defined dynamically here, PHPStan does not see it
            eval('?>' . $compiled);
        }

        return new $class();
    }
}
