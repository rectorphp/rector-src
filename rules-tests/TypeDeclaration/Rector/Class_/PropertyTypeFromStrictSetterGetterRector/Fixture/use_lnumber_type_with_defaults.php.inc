<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class UseLNumberTypeWithDefaults
{
    private $test;

    public function getTest(): int
    {
        return $this->test;
    }

    public function setTest(int $test): void
    {
        $this->test = $test;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class UseLNumberTypeWithDefaults
{
    private int $test = 0;

    public function getTest(): int
    {
        return $this->test;
    }

    public function setTest(int $test): void
    {
        $this->test = $test;
    }
}

?>
