<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithTypedParam;

class ExtendsParentDefaultEmptyArrayString extends ParentWithTypedParam {
    public function baz() {
        echo parent::baz();
    }

    public function boo() {
        echo parent::boo();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithTypedParam;

class ExtendsParentDefaultEmptyArrayString extends ParentWithTypedParam {
    public function baz($default = []) {
        echo parent::baz();
    }

    public function boo($default = '') {
        echo parent::boo();
    }
}

?>
