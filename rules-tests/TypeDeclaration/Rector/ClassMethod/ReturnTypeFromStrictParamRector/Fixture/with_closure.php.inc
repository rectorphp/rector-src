<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class WithClosure
{
    public function doFoo() {
        $c = function (WithClosure $param) {
            return $param;
        };
        return $c;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class WithClosure
{
    public function doFoo() {
        $c = function (WithClosure $param): WithClosure {
            return $param;
        };
        return $c;
    }
}

?>
