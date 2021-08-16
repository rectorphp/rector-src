<?php

use Ds\Vector;

/**
 * @template TValue
 *
 * @implements IteratorAggregate<int, TValue>
 */
namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class ImmutableVector implements Countable, IteratorAggregate
{
    /**
     * @return iterable<TValue>|null
     */
    public function getVector(): ?iterable
    {
    }
}
?>
