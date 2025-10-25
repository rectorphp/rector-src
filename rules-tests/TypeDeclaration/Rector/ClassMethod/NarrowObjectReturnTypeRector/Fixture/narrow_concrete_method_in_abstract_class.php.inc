<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class Product
{
}

abstract class AbstractProductFactory
{
    public function create(): object
    {
        return new Product();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class Product
{
}

abstract class AbstractProductFactory
{
    public function create(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture\Product
    {
        return new Product();
    }
}

?>