<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

use Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Source\SomeAbstractClass;

abstract class SkipCallAbstractMethod extends SomeAbstractClass {
    public function executing(bool $param)
    {
        $this->doVariadic(...$this->getArgs($param));
    }
}