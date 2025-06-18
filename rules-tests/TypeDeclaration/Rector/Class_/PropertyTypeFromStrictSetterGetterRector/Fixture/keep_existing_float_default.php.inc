<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class KeepExistingFloatDefault
{
    private $test = 12.3;

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

final class KeepExistingFloatDefault
{
    private float $test = 12.3;

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
