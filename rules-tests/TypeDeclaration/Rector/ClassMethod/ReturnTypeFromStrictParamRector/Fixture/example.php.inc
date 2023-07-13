<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;
class Example {
    public function doFoo(Example $param) {
        return $param;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;
class Example {
    public function doFoo(Example $param): Example {
        return $param;
    }
}

?>
