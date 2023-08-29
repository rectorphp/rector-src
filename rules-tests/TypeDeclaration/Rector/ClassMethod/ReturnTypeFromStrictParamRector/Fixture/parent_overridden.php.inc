<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Source\ParentOverrideBase;

class ParentOverridden extends ParentOverrideBase {
    public function doFoo(ParentOverrideBase $param) {
        return $param;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Source\ParentOverrideBase;

class ParentOverridden extends ParentOverrideBase {
    public function doFoo(ParentOverrideBase $param): ParentOverrideBase {
        return $param;
    }
}

?>
