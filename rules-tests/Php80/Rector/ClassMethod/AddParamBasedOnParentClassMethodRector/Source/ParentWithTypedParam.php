<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source;

class ParentWithTypedParam
{
    public function execute(int $foo)
    {
    }

    public function emptyArray($default = []) {
        return implode('', $default);
    }

    public function emptyString($default = '') {
        return $default;
    }

    public function nonEmptyArray($default = ['some data']) {
        return implode('', $default);
    }

    public function nonEmptyString($default = 'some value') {
        return $default;
    }
}
