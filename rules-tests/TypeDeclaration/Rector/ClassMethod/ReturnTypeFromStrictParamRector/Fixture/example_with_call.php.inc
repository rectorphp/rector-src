<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class ExampleWithCall {
    public function doFoo(ExampleWithCall $param) {
        $this->doBar($param);

        return $param;
    }

    public function doBar($ref) {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class ExampleWithCall {
    public function doFoo(ExampleWithCall $param): ExampleWithCall {
        $this->doBar($param);

        return $param;
    }

    public function doBar($ref) {}
}

?>
