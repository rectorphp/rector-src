<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class Product
{
}

class Service
{
}

final class MixedFactory
{
    public function create(): object
    {
        if (rand(0, 1)) {
            return new Product();
        }

        return new Service();
    }
}

?>