<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipNonExistingMethod
{
    public function run()
    {
        $name = [$this, 'another'];
    }

    public function name()
    {
    }
}
