<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

class SkipFluentNoReturnType2
{
    public function foo()
    {
        return $this
            ->bar()
            ->baz();
    }

    private function bar()
    {
        return $this;
    }

    private function baz()
    {
        return $this;
    }
}