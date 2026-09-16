<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class SkipWrappedCall
{
    public function run(?SomeObject $someObject): string
    {
        return null !== $someObject ? strtoupper($someObject->getName()) : '';
    }
}
