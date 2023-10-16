<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

class SkipClassMethodUnionTypeObjectNull
{
    public function doSomething() {
        /** @var object|null $bar */
        $bar = $fooo->getBar();
        $bat = $bar->some_old();
    }
}
