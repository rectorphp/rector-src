<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source;

class ParentWithParamWithDefaultValue
{
    public function execute($foo = true)
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

    public function intParam($default = 123) {
        return $default;
    }

    public function floatParam($default = 1.23) {
        return $default;
    }
}
