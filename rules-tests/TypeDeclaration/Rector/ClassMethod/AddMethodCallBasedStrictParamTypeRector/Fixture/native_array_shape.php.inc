<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape {
    private function doFoo() {
        $shape = pathinfo('/www/htdocs/inc/lib.inc.php');
        $this->doBar($shape['basename']);
    }

    private function doBar($param) {

    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class NativeArrayShape {
    private function doFoo() {
        $shape = pathinfo('/www/htdocs/inc/lib.inc.php');
        $this->doBar($shape['basename']);
    }

    private function doBar(string $param) {

    }
}
?>
