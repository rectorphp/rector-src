<?php

namespace Rector\Tests\Instanceof_\Rector\Ternary\FlipNegatedTernaryInstanceofRector\Fixture;

use Rector\Tests\Instanceof_\Rector\Ternary\FlipNegatedTernaryInstanceofRector\Source\TypeForCheck;

final class SkipNoCond
{
    public function run($object)
    {
        echo ! $object instanceof TypeForCheck ?: null;
    }
}
