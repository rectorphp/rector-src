<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class SkipTrueFalseToBecomeBool
{
    public function run(): false|string
    {
        return false;
    }

    public function run(): true|string
    {
        return true;
    }
}
