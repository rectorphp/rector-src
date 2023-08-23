<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultEmptyArrayString extends ParentWithParamWithDefaultValue {
    public function intParam() {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultEmptyArrayString extends ParentWithParamWithDefaultValue {
    public function intParam($default = 123) {
    }
}

?>
