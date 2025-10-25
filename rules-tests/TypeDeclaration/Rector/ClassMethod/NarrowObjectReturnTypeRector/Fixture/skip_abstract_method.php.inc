<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

abstract class AbstractItemFactory
{
    abstract public function create(): object;
}

?>