<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

interface SomeInterface {}

class FirstImplementation implements SomeInterface {}

class SecondImplementation implements SomeInterface {}

class WithInterfaceAndItsImplementation
{
    private SomeInterface $x;

    private SomeInterface $y;

    private ?SomeInterface $z;

    public function __construct(
        FirstImplementation $x,
        SecondImplementation $y = new SecondImplementation(),
        SecondImplementation $z = null,
    ) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

interface SomeInterface {}

class FirstImplementation implements SomeInterface {}

class SecondImplementation implements SomeInterface {}

class WithInterfaceAndItsImplementation
{
    public function __construct(private FirstImplementation $x, private SecondImplementation $y = new SecondImplementation(), private ?SecondImplementation $z = null)
    {
    }
}

?>
