<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape {
    private function doFoo() {
        $shape = pathinfo();
        $this->doBar($shape['basename']);
    }

    private function doBar($param) {

    }
}
?>
-----
<?php


namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape
{
    private function doFoo()
    {
        $shape = pathinfo();
        $this->doBar($shape['basename']);
    }

    private function doBar(string $param)
    {

    }
}
?>
