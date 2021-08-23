<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use App\Library\ValueObjects\Month;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class TestClass
{
	/**
	 * @ORM\Column(type = "library_month")
	 */
	public Month $since;

	/**
	 * @ORM\Column(type = "string")
	 */
	public string $rate;

	public function __construct(Month $validFrom, string $rate)
	{
		$this->since = $validFrom;
		$this->rate = $rate;
	}
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use App\Library\ValueObjects\Month;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class TestClass
{
	/**
	 * @ORM\Column(type = "library_month")
	 */
	public Month $since;

	public function __construct(
    Month $validFrom,
    public string $rate
  )
	{
		$this->since = $validFrom,
    /**
	   * @ORM\Column(type = "string")
	   */
		$this->rate = $rate
	}
}
?>
