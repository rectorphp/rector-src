<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class SkipVarProperty
{
    var $id;

    public function __construct($id) {
        $this->id = $id;
    }
}
