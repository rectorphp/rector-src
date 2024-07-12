<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class SkipInStaticClosure
{
    public function run()
    {
        return static function () {
            return self::method("string");
        };
    }

    private static function method(?string $value)
    {
        return $value;
    }
}
