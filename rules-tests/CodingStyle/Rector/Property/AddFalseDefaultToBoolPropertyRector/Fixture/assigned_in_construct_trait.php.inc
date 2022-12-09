<?php

namespace Rector\Tests\CodingStyle\Rector\Property\AddFalseDefaultToBoolPropertyRector\Fixture;

// __construct can be overridden by class that consume it, @see https://3v4l.org/MZFmT#v7.0.28
trait AssignInConstructTrait
{
    /**
     * @var bool
     */
    private $property;

    public function __construct(bool $property)
    {
        $this->property = $property;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\AddFalseDefaultToBoolPropertyRector\Fixture;

// __construct can be overridden by class that consume it, @see https://3v4l.org/MZFmT#v7.0.28
trait AssignInConstructTrait
{
    /**
     * @var bool
     */
    private $property = false;

    public function __construct(bool $property)
    {
        $this->property = $property;
    }
}

?>
