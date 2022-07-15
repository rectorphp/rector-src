<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

/**
 * Both interface and class on same file on purpose to ensure locate
 * parent interface bug
 */
interface InterfaceWithParam
{
    public function execute($foo);
}

class ImplementsInterface implements InterfaceWithParam {
    public function execute()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

/**
 * Both interface and class on same file on purpose to ensure locate
 * parent interface bug
 */
interface InterfaceWithParam
{
    public function execute($foo);
}

class ImplementsInterface implements InterfaceWithParam {
    public function execute($foo)
    {
    }
}

?>
