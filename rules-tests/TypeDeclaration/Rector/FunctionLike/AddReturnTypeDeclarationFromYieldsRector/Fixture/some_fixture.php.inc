<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class SomeFixture
{
    public function someFunction()
    {
        yield 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class SomeFixture
{
    public function someFunction(): \Generator
    {
        yield 1;
    }
}

?>
