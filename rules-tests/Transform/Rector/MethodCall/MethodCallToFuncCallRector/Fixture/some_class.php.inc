<?php

namespace Rector\Tests\Transform\Rector\MethodCall\MethodCallToFuncCallRector\Fixture;

use Rector\Tests\Transform\Rector\MethodCall\MethodCallToFuncCallRector\Source\ParentControllerWithRender;

final class SomeClass extends ParentControllerWithRender
{
    public function show()
    {
        return $this->render('some_template');
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\MethodCall\MethodCallToFuncCallRector\Fixture;

use Rector\Tests\Transform\Rector\MethodCall\MethodCallToFuncCallRector\Source\ParentControllerWithRender;

final class SomeClass extends ParentControllerWithRender
{
    public function show()
    {
        return \view('some_template');
    }
}

?>
