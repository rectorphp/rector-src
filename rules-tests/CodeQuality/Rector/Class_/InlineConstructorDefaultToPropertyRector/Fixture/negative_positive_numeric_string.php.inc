<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class NegativePositiveInt
{
    private $propertyA;
    private $propertyB;
    private $propertyC;

    public function __construct()
    {
        $this->propertyA = -"100";
        $this->propertyB = "100";
        $this->propertyC = +"100";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class NegativePositiveInt
{
    private $propertyA = -"100";
    private $propertyB = "100";
    private $propertyC = +"100";

    public function __construct()
    {
    }
}

?>
