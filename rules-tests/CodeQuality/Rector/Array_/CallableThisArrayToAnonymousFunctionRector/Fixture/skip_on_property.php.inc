<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

final class SkipOnProperty
{
    private $prop = [SkipOnProperty::class, 'size'];

    public function size()
    {
    }
}