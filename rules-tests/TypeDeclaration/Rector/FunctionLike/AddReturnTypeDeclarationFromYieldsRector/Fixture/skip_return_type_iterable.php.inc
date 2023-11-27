<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class SkipReturnTypeIterable
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
