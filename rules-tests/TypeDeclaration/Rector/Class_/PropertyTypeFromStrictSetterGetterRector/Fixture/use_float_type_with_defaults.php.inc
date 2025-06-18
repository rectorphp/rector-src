<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class UseFloatTypeWithDefaults
{
    private $test;

    public function getTest(): float
    {
        return $this->test;
    }

    public function setTest(float $test): void
    {
        $this->test = $test;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class UseFloatTypeWithDefaults
{
    private float $test = 0.0;

    public function getTest(): float
    {
        return $this->test;
    }

    public function setTest(float $test): void
    {
        $this->test = $test;
    }
}

?>
