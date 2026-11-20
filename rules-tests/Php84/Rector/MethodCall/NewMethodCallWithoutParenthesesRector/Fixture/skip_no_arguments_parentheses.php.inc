<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class SkipNoArgumentsParentheses
{
    public function run()
    {
        (new Request)->withMethod('GET');
        $message = (new Regex)->message;
        (new Request)?->withMethod('GET');
        $constant = (new Request)::METHOD_GET;
        $value = (new \ArrayObject)['key'];
        $result = (new Invokable)();
    }
}
