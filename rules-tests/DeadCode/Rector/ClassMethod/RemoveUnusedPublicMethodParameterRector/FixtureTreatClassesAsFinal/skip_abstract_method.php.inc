<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\FixtureTreatClassesAsFinal;

abstract class SkipAbstractMethod
{
    abstract public function run($a, $b);
}
