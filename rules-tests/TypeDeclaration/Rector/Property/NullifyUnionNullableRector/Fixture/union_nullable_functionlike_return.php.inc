<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableFunctionLikeReturn
{
    public function run(): null|array
    {
        return null;
    }

    public function run2(): null|array
    {
        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableFunctionLikeReturn
{
    public function run(): ?array
    {
        return null;
    }

    public function run2(): ?array
    {
        return null;
    }
}

?>
