<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class SkipNullableReturn
{
    public function run(?SomeObject $someObject): ?string
    {
        return null !== $someObject ? $someObject->findName() : '';
    }
}
