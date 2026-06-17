<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector\Fixture;

class NoInnerFunction
{
    public function run(): void
    {
        echo 'hello';
    }
}
