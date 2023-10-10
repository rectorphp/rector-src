<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

final class SkipIndirectVariableDefinition
{
    public function get()
    {
        /** @var MyClass<T>*/
        $x = new MyClass;
    }
}
