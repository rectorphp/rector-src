<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

class SkipVoid
{
    public function foo()
    {
        $this->bar();
    }

    private function bar()
    {
        echo 'Hello, world!';
    }
}