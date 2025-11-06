<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

final class FinalProductFactory
{
    public function create(): \DateTime
    {
        return new \DateTime();
    }
}

?>