<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class SkipEmptyYield
{
    public function someFunction()
    {
        yield;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class SkipEmptyYield
{
    public function someFunction(): \Generator
    {
        yield;
    }
}

?>
