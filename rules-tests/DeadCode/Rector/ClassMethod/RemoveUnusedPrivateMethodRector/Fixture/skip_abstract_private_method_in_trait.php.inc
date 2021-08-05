<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

trait MyTrait
{
    abstract private function privateMethod();

    public function publicMethod() {
        $this->privateMethod();
    }
}

class MyClass
{
    use MyTrait;

    private function privateMethod()
    {
        //code
    }
}
?>
