<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

final class SkipOnClassConst
{
    private const CLASS_CONST = [SkipOnClassConst::class, 'size'];

    public function size()
    {
    }
}
