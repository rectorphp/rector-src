<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class CoalesceNonNullableParam
{
    public function run()
    {
        fn (int $value) => $value ?? null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class CoalesceNonNullableParam
{
    public function run()
    {
        fn (int $value): int => $value ?? null;
    }
}

?>
