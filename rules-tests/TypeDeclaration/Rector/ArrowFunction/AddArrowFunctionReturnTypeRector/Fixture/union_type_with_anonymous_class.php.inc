<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class UnionTypeWithAnonymousClass
{
    public function run()
    {
        fn () => rand(0, 1) ? new class {} : true;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class UnionTypeWithAnonymousClass
{
    public function run()
    {
        fn () => rand(0, 1) ? new class {} : true;
    }
}

?>
