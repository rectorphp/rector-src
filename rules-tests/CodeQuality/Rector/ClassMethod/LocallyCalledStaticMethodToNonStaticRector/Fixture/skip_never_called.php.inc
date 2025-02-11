<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class SkipNeverCalled
{
    public function run()
    {
    }

    private static function someStatic(): array
    {
        return [];
    }
}
