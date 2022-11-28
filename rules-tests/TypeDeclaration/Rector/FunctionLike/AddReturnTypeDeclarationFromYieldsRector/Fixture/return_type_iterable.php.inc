<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class ReturnTypeIterable
{
    public function run(): iterable
    {
        yield from $this->getData();
    }

    private function getData(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class ReturnTypeIterable
{
    public function run(): \Iterator
    {
        yield from $this->getData();
    }

    private function getData(): array
    {
        return [];
    }
}

?>
