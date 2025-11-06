<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

final class SkipStandaloneNull
{
    public function foo(): ?string
    {
        return null;
    }

    public function foo(): string|null
    {
        return null;
    }
}

?>
