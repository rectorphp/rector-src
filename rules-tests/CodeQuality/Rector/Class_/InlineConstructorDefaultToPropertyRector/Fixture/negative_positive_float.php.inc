<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class NegativePositiveFloat
{
    private $propertyA;
    private $propertyB;
    private $propertyC;

    public function __construct()
    {
        $this->propertyA = -100.1;
        $this->propertyB = 100;
        $this->propertyC = +100.2;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class NegativePositiveFloat
{
    private $propertyA = -100.1;
    private $propertyB = 100;
    private $propertyC = +100.2;

    public function __construct()
    {
    }
}

?>
