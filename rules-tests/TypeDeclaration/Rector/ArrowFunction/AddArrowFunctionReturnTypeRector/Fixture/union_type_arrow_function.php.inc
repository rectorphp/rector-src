<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class Fixture
{
    public function run()
    {
        fn () => rand(0, 1) ? true : (rand(0, 1) ? [] : 0);
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
        fn (): array|bool|int => rand(0, 1) ? true : (rand(0, 1) ? [] : 0);
    }
}

?>
