<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class GenericProperty
{
	/**
	 * @var iterable<string>
	 */
	private iterable $property;

	/**
	 * @param iterable<string> $property
	 */
	public function __construct(iterable $property) {
		$this->property = $property;
	}
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class GenericProperty
{
	/**
	 * @param iterable<string> $property
	 */
	public function __construct(private iterable $property) {
	}
}
?>
