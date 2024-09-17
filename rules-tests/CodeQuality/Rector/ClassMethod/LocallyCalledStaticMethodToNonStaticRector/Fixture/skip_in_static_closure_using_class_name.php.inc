<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class SkipInStaticClosureUsingClassName
{
    public function run()
    {
        return static function () {
            return SkipInStaticClosureUsingClassName::method("string");
        };
    }

    private static function method(?string $value)
    {
        return $value;
    }
}
