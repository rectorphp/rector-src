<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

class ImplementsInterfaceFlipped implements InterfaceWithParamFlipped {
    public function execute()
    {
    }
}

/**
 * Both interface and class on same file on purpose to ensure locate
 * parent interface bug
 */
interface InterfaceWithParamFlipped
{
    public function execute($foo);
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

class ImplementsInterfaceFlipped implements InterfaceWithParamFlipped {
    public function execute($foo)
    {
    }
}

/**
 * Both interface and class on same file on purpose to ensure locate
 * parent interface bug
 */
interface InterfaceWithParamFlipped
{
    public function execute($foo);
}

?>
