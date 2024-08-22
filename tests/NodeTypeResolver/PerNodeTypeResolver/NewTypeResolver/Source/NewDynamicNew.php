<?php

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver\Source;

final class NewDynamicNew
{
    public function run($class)
    {
        new $class;
    }
}
