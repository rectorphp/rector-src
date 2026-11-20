<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class SkipNotNew
{
    public function run()
    {
        $message = ($this->regex)->message;
        $value = ($this->items)['key'];
        $result = ($this->callback)();
    }
}
