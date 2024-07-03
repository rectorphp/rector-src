<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

class SkipFluentNoReturnType
{
    public function foo()
    {
        return $this
            ->bar()
            ->baz()
            ->qux();
    }

    private function bar()
    {
        return $this;
    }

    private function baz()
    {
        return $this;
    }

    public function qux()
    {
        return $this;
    }
}