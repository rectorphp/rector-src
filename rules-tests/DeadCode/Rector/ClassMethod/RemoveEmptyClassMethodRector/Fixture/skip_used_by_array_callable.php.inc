<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source\MyDIContainer;

final class SkipUsedByArrayCallable
{
    public function run()
    {
        $container = new MyDIContainer();
        $container->call([$this, 'doFoo']);
    }

    public function doFoo() {
    }
}
