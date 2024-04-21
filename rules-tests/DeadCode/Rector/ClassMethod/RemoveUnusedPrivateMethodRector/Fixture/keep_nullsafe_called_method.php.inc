<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

final class KeepNullSafeCalledMethod
{
    private function privateMethod():void {}

    public function pubMethod() {
        $this->getX()?->privateMethod();
    }

    public function getX(): KeepNullSafeCalledMethod|null {
        return rand(0,1) ? new KeepNullSafeCalledMethod() : null;
    }
}
