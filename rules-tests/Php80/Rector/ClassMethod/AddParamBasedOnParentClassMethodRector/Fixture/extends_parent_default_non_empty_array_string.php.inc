<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithTypedParam;

class ExtendsParentDefaultEmptyArrayString extends ParentWithTypedParam {
    public function nonEmptyArray() {
        echo parent::nonEmptyArray();
    }

    public function nonEmptyString() {
        echo parent::nonEmptyString();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithTypedParam;

class ExtendsParentDefaultEmptyArrayString extends ParentWithTypedParam {
    public function nonEmptyArray($default = ['some data']) {
        echo parent::nonEmptyArray();
    }

    public function nonEmptyString($default = 'some value') {
        echo parent::nonEmptyString();
    }
}

?>
