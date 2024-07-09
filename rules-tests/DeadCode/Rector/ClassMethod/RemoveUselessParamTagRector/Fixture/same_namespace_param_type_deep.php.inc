<?php

// using Source namespace on purpose to test same namespace for used class
namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source;

class SameNamespaceParamTypeDeep
{
    /**
     * @param Deep\SomeClass $someClass
     */
    function foo(Deep\SomeClass $someClass)
    {
    }
}

?>
-----
<?php

// using Source namespace on purpose to test same namespace for used class
namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source;

class SameNamespaceParamTypeDeep
{
    function foo(Deep\SomeClass $someClass)
    {
    }
}

?>
