<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanOr\RepeatedOrEqualToInArrayRector\Fixture;

final class SkipMixEqualAndIdentical
{
    public function demo()
    {
        $a = false;
        var_dump($a == 'a' || $a == 'b' || $a === null);
    }
}
