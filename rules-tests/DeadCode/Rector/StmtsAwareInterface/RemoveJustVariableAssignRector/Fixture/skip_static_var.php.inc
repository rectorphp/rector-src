<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

class SkipStaticVar
{
    private $property;

    function init()
    {
        static $result = null;

        if (! isset($result)) {
            $result = 1;
            $this->property = $result;
        }
    }
}