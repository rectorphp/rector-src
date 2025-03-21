<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class SkipWithoutParentheses
{
    public function run()
    {
        new Request()->withMethod('GET')->withUri('/hello-world');
    }
}

