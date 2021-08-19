<?php

interface X {}
namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class Y implements X {}
namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class Z {}

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class Foo
{
	public Y $y;

	public Z $z;

	public function __construct(Y $y, Z $z)
	{
		$this->y = $y;
		$this->z = $z;
	}

	public function getX(): X
	{
		return $this->y;
	}
}
?>
-----
<?php

interface X {}
class Y implements X {}
class Z {}

final class Foo
{
	public function __construct(public Y $y, public Z $z)
	{
	}

	public function getX(): X
	{
		return $this->y;
	}
}
?>
