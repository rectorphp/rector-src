<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape3 {
    private function doFoo() {
        $arr = [];
        $arr[10] = [123.132];
        $this->doBar($arr[10]);
    }

    private function doBar($param) {

    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape3 {
    private function doFoo() {
        $arr = [];
        $arr[10] = [123.132];
        $this->doBar($arr[10]);
    }

    private function doBar(array $param) {

    }
}
?>
