<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanOr\RepeatedOrEqualToInArrayRector\Fixture;

final class SkipNotInlineIdentical
{
    public function demo($someVariable): bool
    {
        if ($someVariable === 'a' || $someVariable === 'b' || execute(fn(): bool => $someVariable === 'c')) {
            return true;
        }

        return false;
    }
}
