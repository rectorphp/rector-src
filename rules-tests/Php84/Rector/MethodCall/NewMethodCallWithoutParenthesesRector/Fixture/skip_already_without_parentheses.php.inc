<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class SkipAlreadyWithoutParentheses
{
    public function run()
    {
        $message = new Regex('some-pattern')->message;
        new Request()?->withMethod('GET');
        $constant = new Request()::METHOD_GET;
        $value = new \ArrayObject(['key' => 'value'])['key'];
        $result = new Invokable()();
    }
}
