<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class CoalesceNullableParam
{
    public function run()
    {
        fn (?string $value) => $value ?? null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class CoalesceNullableParam
{
    public function run()
    {
        fn (?string $value): ?string => $value ?? null;
    }
}

?>
