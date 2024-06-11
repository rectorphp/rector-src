<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class OnStaticCall {
    private function doFoo() {
        $shape = pathinfo('/www/htdocs/inc/lib.inc.php');
        self::doBar($shape['basename']);
    }

    private static function doBar($param) {

    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class OnStaticCall {
    private function doFoo() {
        $shape = pathinfo('/www/htdocs/inc/lib.inc.php');
        self::doBar($shape['basename']);
    }

    private static function doBar(string $param) {

    }
}
?>
