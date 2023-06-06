<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipAnonymousClassAsRisky
{
    private $var;

    public function foo()
    {
        return new class
        {
            private $var;

            public function bar(){
                return $this->var;
            }
        };
    }
}
