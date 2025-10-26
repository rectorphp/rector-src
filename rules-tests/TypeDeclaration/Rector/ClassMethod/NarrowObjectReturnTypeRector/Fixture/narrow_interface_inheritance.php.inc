<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

interface ProductInterface
{
}

final class ConcreteProduct implements ProductInterface
{
}

abstract class AbstractProductFactory
{
    abstract public function build(): ProductInterface;
}

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

interface ProductInterface
{
}

final class ConcreteProduct implements ProductInterface
{
}

abstract class AbstractProductFactory
{
    abstract public function build(): ProductInterface;
}

final class ConcreteProductFactory extends AbstractProductFactory
{
    public function build(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture\ConcreteProduct
    {
        return $this->createProduct();
    }

    private function createProduct(): ConcreteProduct
    {
        return new ConcreteProduct();
    }
}

?>