<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class SkipPhpdocArrayShape {
    private function doFoo() {
        $shape = $this->returnsShape();
        return $shape['hello'];
    }

    /**
     * @return array{'hello': string, 'world': int}
     */
    private function returnsShape() {

    }

}
