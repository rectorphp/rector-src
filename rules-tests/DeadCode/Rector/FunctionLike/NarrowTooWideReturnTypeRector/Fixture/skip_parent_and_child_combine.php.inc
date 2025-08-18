<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class SkipParentAndChildCombine
{
    public function run(): \DateTimeInterface|\DateTimeImmutable
    {
        return rand(0, 1)
            ? new \DateTime('now')
            : new \DateTimeImmutable('now');
    }
}
