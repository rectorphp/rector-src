<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipOnParam
{
    public function size()
    {
    }

    public function run(array $callback = [SkipOnParam::class, 'size'])
    {
    }
}
