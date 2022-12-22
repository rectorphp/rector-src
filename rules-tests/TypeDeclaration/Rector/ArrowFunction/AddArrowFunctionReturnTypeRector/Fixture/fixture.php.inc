<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class Fixture
{
    public function run()
    {
        fn () => rand(0, 1) ? []: null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class Fixture
{
    public function run()
    {
        fn (): ?array => rand(0, 1) ? []: null;
    }
}

?>
