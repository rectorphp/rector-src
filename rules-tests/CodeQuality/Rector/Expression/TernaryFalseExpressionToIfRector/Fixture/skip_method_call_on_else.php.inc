<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

final class SkipMethodCallOnElse
{
    public function run(bool $param): void
    {
        $param ? $this->a() : $this->b();
    }

    public function a(): void
    {
        new HeavyObject1();
    }

    public function b(): null
    {
        new HeavyObject2();
		return 'b';
    }
}
