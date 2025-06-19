<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\FixtureTreatClassesAsFinal;

class SkipAbstractMethod
{
    abstract public function run($a, $b);
}