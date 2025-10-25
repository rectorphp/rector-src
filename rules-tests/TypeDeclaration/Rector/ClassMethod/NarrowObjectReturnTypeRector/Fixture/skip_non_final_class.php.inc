<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

class NonFinalFactory
{
    public function create(): object
    {
        return new ConferenceTalk();
    }
}

?>