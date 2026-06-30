<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipCallbackKeyedArray
{
    public function run()
    {
        $config = [
            'callback' => [$this, 'name'],
        ];
    }

    public function name()
    {
    }
}
