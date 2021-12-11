<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithTypedParam;

// ref https://3v4l.org/4Mgrt
class B extends ParentWithTypedParam{
    public function execute()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithTypedParam;

// ref https://3v4l.org/4Mgrt
class B extends ParentWithTypedParam{
    public function execute($foo)
    {
    }
}

?>
