<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanOr\RepeatedOrEqualToInArrayRector\Fixture;

final class SkipTwoOrLess
{
    public function demo($someVariable): bool
    {
        if ($someVariable === 'a' || $someVariable === 'b') {
            return true;
        }

        return false;
    }
}
