<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipNativeOptionalShape {
    private function doFoo() {
        $shape = pathinfo('');
        $this->doBar($shape['dirname']); // dirname is only conditionally returned
    }

    private function doBar($param) {

    }
}
?>
