<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class ReturnIterator
{
    public function someFunction(): iterable
    {
        yield 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class ReturnIterator
{
    public function someFunction(): \Iterator
    {
        yield 1;
    }
}

?>
