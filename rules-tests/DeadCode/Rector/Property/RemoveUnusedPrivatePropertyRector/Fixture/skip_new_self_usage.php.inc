<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class SkipNewSelfUsage
{
    private $var;

    public function __construct($var)
    {
        $this->var = $var;
    }

    public function run($var)
    {
        $obj = new self($var);
        echo $obj->var;
    }
}
