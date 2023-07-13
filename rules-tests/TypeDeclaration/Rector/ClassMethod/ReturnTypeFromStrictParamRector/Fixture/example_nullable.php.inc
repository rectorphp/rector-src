<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;
class ExampleNullable {
    public function doFoo(?ExampleNullable $param) {
        return $param;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;
class ExampleNullable {
    public function doFoo(?ExampleNullable $param): ?ExampleNullable {
        return $param;
    }
}

?>
