<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape2 {
    private function doFoo() {
        $arr = [];
        $arr[10] = 'foo';
        $this->doBar($arr[10]);
    }

    private function doBar($param) {

    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape2 {
    private function doFoo() {
        $arr = [];
        $arr[10] = 'foo';
        $this->doBar($arr[10]);
    }

    private function doBar(string $param) {

    }
}
?>
