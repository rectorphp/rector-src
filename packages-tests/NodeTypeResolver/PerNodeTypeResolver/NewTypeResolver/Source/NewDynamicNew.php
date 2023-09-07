<?php

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver\Source;

class NewDynamicNew
{
    public function run($class)
    {
        new $class;
    }
}
