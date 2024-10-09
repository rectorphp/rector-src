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
        // the dead code set can remove unreachable stmt part :)
        // @see https://getrector.com/demo/ad3358be-5aaf-4aa3-85cf-c239c3eb3fd8
        return null;
    }
}
