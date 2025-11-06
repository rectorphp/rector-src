<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

final class SkipNull
{
    public function run(): ?string
    {
        return null;
    }
}
