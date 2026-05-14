<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Source;

interface InterfaceWithMethod
{
    public function run($a, $b);
}
