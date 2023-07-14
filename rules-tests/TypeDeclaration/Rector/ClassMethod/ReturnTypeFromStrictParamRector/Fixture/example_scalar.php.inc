<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class ExampleInt {
    public function doFoo(int $param) {
        return $param;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class ExampleInt {
    public function doFoo(int $param): int {
        return $param;
    }
}

?>
