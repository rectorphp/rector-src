<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultIntOrFloat extends ParentWithParamWithDefaultValue {
    public function intParam() {
    }

    public function floatParam() {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultIntOrFloat extends ParentWithParamWithDefaultValue {
    public function intParam($default = 123) {
    }

    public function floatParam($default = 1.23) {
    }
}

?>
