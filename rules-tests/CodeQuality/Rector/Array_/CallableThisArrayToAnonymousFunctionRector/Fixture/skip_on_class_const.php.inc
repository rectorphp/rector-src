<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

final class SkipOnClassConst
{
    private const CLASS_CONST = [SkipOnClassConst::class, 'size'];

    public function size()
    {
    }
}