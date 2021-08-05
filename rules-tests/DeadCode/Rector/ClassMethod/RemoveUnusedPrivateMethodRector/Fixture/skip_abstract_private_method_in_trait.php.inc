<?php

trait MyTrait
{
    abstract private function privateMethod();
        
    public function publicMethod() {
        $this->privateMethod();
    }
}

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

class MyClass
{
    use MyTrait;

    private function privateMethod()
    {
        //code
    }

}
?>
