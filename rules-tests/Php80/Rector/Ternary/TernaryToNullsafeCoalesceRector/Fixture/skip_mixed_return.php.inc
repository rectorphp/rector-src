<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class SkipMixedReturn
{
    public function run(?SomeObject $someObject): mixed
    {
        return null !== $someObject ? $someObject->findMixed() : '';
    }

    public function runUntyped(?SomeObject $someObject): mixed
    {
        return null !== $someObject ? $someObject->findUntyped() : '';
    }

    public function runUntypedProperty(?SomeObject $someObject): mixed
    {
        return null !== $someObject ? $someObject->untypedName : '';
    }
}
