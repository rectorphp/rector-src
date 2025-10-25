<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

final class FinalProduct
{
}

final class FinalProductFactory
{
    public function create(): FinalProduct
    {
        return new FinalProduct();
    }
}

?>