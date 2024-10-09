<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector\Fixture;

final class SkipWithElse
{
    private ?int $foo = null;
    private ?int $bar = null;

    public function isTrue($value)
    {
        if ($this->foo !== null) {
            return $this->foo;
        } else {
            return $this->bar;
        }

        // while unreachable, above code is valid and should not be simplified by this rule
        // the dead code set can remove unreachable stmt part :),
        // and after that, early return set can clean this up
        // @see https://getrector.com/demo/ad3358be-5aaf-4aa3-85cf-c239c3eb3fd8
        // @see https://getrector.com/demo/6cbb4273-dff2-45a0-82f0-f6fe932d0188
        return null;
    }
}
