<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

final class SkipUsedAsClosure
{
    public function foo(): \Closure
    {
        return \Closure::fromCallable(self::bar(...));
    }

    private static function bar(): void
    {
        echo 'Hello world';
    }
}

?>
