<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class CombineUnion
{
    private $test;

    public function getTest(): string
    {
        return $this->test;
    }

    public function setTest(?string $test): void
    {
        $this->test = $test;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class CombineUnion
{
    private ?string $test = null;

    public function getTest(): string
    {
        return $this->test;
    }

    public function setTest(?string $test): void
    {
        $this->test = $test;
    }
}

?>
