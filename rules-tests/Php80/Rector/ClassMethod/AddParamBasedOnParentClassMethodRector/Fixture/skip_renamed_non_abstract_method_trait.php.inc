<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ValuesTrait;

trait SkipRenamedNonAbstractMethodTrait {
    use ValuesTrait {
        addValue as addAnyValue;
    }

    public function addValue() {
        $this->addAnyValue(0);
    }
}