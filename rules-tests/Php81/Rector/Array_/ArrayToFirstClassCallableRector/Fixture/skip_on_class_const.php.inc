<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipOnClassConst
{
    private const CLASS_CONST = [SkipOnClassConst::class, 'size'];

    private const CLASS_CONST_NESTED = [
        'hey' => [SkipOnClassConst::class, 'size']
    ];

    public function size()
    {
    }
}
