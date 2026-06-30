<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipFactoryKeyedArray
{
    public function run()
    {
        $config = [
            'factory' => [$this, 'name'],
        ];
    }

    public function name()
    {
    }
}
