<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Symfony\Component\Validator\Constraints as Assert;

class Y {}
class Z {}

final class X
{
	public Y $y;

	/**
	 * @var Z[]
	 *
	 * @Assert\Valid()
	 * @Assert\NotBlank()
	 */
	public array $z = [];

	/**
	 * @param Z[] $z
	 */
	public function __construct(Y $y, array $z = [])
	{
		$this->y = $y;
		$this->z = $z;
	}
}
?>
-----
<?php

// Not sure what's correct. I don't know if promoted properties can have a docblock with annotations. But it certainly shouldn't just delete my annotations.
