<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector\Fixture;

final class SomeClass
{
    public function run()
    {
        return $this->first() && true;
    }

    public function first()
    {
        return true;
    }
}

?>
