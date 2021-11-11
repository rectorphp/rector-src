<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class SkipPropertyFetchUsedInAssignExpr2
{
    private string $foo;

    public function __construct(int $foo)
    {
        $this->foo = $foo;
    }

    public function calculateMac(): int
    {
        $foobar = $this->foo++;
        return $foobar;
    }
}
