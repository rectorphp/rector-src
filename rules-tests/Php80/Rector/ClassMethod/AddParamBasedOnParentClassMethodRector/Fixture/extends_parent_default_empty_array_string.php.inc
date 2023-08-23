<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultEmptyArrayString extends ParentWithParamWithDefaultValue {
    public function emptyArray() {
    }

    public function emptyString() {
    }

    public function emptyString2() {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParamWithDefaultValue;

class ExtendsParentDefaultEmptyArrayString extends ParentWithParamWithDefaultValue {
    public function emptyArray($default = []) {
    }

    public function emptyString($default = '') {
    }

    public function emptyString2($default = '') {
    }
}

?>
