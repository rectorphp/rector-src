<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

class SkipStaticVar
{
    private $property;

    function init($value)
    {
        static $result = null;

        if (! isset($result)) {
            $result = $value;
            $this->property = $result;
        }
    }
}