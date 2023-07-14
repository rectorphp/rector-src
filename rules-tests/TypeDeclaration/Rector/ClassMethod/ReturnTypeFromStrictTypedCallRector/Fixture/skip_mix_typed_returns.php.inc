<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipMixedTypeReturns
{
    public function getData()
    {
        if (rand(0,1)) {
            return 0;
        }

        return $this->differentType();
    }

    public function differentType(): float
    {

    }
}

?>
