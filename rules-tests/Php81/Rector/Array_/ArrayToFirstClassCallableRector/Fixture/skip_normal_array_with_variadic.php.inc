<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipNormalArrayWithVariadic
{
    private function foo(mixed ...$args): void {}

    public function test(): void
    {
        $this->foo('first', ['key' => 'value'], c: 'test');
    }
}
