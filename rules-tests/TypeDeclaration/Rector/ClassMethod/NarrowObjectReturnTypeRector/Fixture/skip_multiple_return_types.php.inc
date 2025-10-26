<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

final class MixedFactory
{
    public function create(): object
    {
        if (rand(0, 1)) {
            return new \DateTime();
        }

        return new \stdClass();
    }
}

?>