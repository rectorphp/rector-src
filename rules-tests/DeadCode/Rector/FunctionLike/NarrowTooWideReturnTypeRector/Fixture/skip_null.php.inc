<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class SkipNull
{
    public function run(): ?string
    {
        return null;
    }
}
