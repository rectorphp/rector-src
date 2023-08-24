<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultEmptyArrayString extends ParentWithParamWithDefaultValue {
    public function nonEmptyArray() {
    }

    public function nonEmptyString() {
    }

    public function nonEmptyString2() {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultEmptyArrayString extends ParentWithParamWithDefaultValue {
    public function nonEmptyArray($default = ['some data']) {
    }

    public function nonEmptyString($default = 'some value') {
    }

    public function nonEmptyString2($default = "some value") {
    }
}

?>
