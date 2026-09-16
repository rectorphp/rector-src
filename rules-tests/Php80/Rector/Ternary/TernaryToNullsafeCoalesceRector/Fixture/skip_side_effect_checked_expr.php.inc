<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class SkipSideEffectCheckedExpr
{
    public function run(): ?string
    {
        return null !== $this->resolve() ? $this->resolve()->getName() : null;
    }

    private function resolve(): ?SomeObject
    {
        return new SomeObject();
    }
}
