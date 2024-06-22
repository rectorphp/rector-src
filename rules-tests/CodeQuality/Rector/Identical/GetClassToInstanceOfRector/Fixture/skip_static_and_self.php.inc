<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\GetClassToInstanceOfRector\Fixture;

final class SkipStaticAndSelf
{
    public function test() {
        if (\get_class($other) !== static::class) {
        }

        if (static::class !== \get_class($other)) {
        }

        return \get_class($other) !== self::class;
    }
}
