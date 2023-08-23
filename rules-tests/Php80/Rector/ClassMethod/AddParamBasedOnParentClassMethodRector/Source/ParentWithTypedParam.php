<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source;

class ParentWithTypedParam
{
    public function execute(int $foo)
    {
    }

    public function baz($default = []) {
        return implode('', $default);
    }

    public function boo($default = '') {
        return $default;
    }
}
