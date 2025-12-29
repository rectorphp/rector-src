<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipFunctionReference
{
    public function run()
    {
        $name = [class_exists($this), 'name'];
    }

    public function name()
    {
    }
}
