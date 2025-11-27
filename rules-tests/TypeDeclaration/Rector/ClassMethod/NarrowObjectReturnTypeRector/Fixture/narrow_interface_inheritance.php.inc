<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\AbstractProductFactory;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ProductInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConcreteProduct;

final class ConcreteProductFactory extends AbstractProductFactory
{
    public function build(): ProductInterface
    {
        return $this->createProduct();
    }

    private function createProduct(): ConcreteProduct
    {
        return new ConcreteProduct();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\AbstractProductFactory;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ProductInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConcreteProduct;

final class ConcreteProductFactory extends AbstractProductFactory
{
    public function build(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConcreteProduct
    {
        return $this->createProduct();
    }

    private function createProduct(): ConcreteProduct
    {
        return new ConcreteProduct();
    }
}

?>