<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class SkipCloneSelf
{
    private $var;

    public function __construct($var)
    {
        $this->var = $var;
    }

    public function run()
    {
        $obj = clone $this;
        echo $obj->var;
    }

    public function run2($var)
    {
        $obj = new self($var);
        echo $obj->var;
    }
}
