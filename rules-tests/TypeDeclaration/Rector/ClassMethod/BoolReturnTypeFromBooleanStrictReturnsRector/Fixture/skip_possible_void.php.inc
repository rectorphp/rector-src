<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

class SkipPossibleVoid
{
    public function foo()
    {
        if (rand(0, 1)) {
            return $this->bar();
        }
    }

    private function bar()
    {
        if (rand(0, 1)) {
            return;
        }

        return 'Hello World';
    }
}