<?php

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver\Source;

class NewDynamicVariable
{
    public function run($class)
    {
        new $class;
    }
}
