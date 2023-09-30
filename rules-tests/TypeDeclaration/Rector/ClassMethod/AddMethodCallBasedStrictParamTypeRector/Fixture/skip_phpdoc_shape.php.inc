<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipPhpdocArrayShape {
    private function doFoo() {
        $shape = $this->returnsShape();
        $this->doBar($shape['hello']);
    }

    /**
     * @return array{'hello': string, 'world': int}
     */
    private function returnsShape() {

    }

    private function doBar($param) {

    }
}
