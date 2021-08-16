<?php

use Ds\Vector;

/**
 * @template TValue
 *
 * @implements IteratorAggregate<int, TValue>
 */
namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

final class ImmutableVector implements Countable, IteratorAggregate
{
	/**
	 * @var Vector<TValue>
	 */
	private Vector $vector;

	/**
	 * @param iterable<TValue>|null $values
	 */
	public function __construct(?iterable $values = null)
	{
		$this->vector = new Vector($values ?? []);
	}
}
?>

