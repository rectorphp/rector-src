<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

final class SkipOnProperty
{
    private $prop = [SkipOnProperty::class, 'size'];

    public function size()
    {
    }
}
