<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use ReflectionMethod;

class SkipInReflectionMethod
{
    function methodInjection()
    {}

    public function get()
    {
        return new ReflectionMethod(...[$this, 'methodInjection']);
    }
}
