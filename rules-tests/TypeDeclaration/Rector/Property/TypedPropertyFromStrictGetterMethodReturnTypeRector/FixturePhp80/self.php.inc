<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector\Fixture;

final class Test
{
    private $test;

    public function getTest(): self
    {
        return $this->test;
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector\Fixture;

final class Test
{
    private self $test;

    public function getTest(): self
    {
        return $this->test;
    }
}
?>
