<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class SkipAnonymousClass
{
    public function run()
    {
        $value = (new class {
            public $value = 1;
        })->value;
        $result = (new class() {
            public function __invoke()
            {
            }
        })();
    }
}
