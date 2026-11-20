<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class SkipDynamicClassNameExpr
{
    public function run()
    {
        $message = (new ($this->resolveClass())())->message;
        $constant = (new ($this->resolveClass())())::METHOD_GET;
        $value = (new ($this->resolveClass())())['key'];
        $result = (new ($this->resolveClass())())();
    }
}
