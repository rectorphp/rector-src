<?php

namespace Rector\Tests\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector\Fixture;

final class SkipNoArray
{
    /**
     * @param int[] $items
     */
    public function run($items)
    {
    }
}
