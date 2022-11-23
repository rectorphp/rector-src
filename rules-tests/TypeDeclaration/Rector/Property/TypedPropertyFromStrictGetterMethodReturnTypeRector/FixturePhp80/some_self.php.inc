<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector\FixturePhp80;

final class SomeSelf
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

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector\FixturePhp80;

final class SomeSelf
{
    private self $test;

    public function getTest(): self
    {
        return $this->test;
    }
}
?>
