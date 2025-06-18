<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ValuesTraitAbstract;

trait OverrideAbstractTraitMethod {
    use ValuesTraitAbstract;

    public function addValue() {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ValuesTraitAbstract;

trait OverrideAbstractTraitMethod {
    use ValuesTraitAbstract;

    public function addValue(int $value) {
    }
}

?>
