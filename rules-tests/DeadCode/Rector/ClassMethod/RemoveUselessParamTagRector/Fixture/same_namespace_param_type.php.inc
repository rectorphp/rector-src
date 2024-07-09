<?php

// using Source namespace on purpose to test same namespace for used class
namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source;

class SameNamespaceParamType
{
    /**
     * @param SomeClass $someClass
     */
    function foo(SomeClass $someClass)
    {
    }
}

?>
-----
<?php

// using Source namespace on purpose to test same namespace for used class
namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source;

class SameNamespaceParamType
{
    function foo(SomeClass $someClass)
    {
    }
}

?>
