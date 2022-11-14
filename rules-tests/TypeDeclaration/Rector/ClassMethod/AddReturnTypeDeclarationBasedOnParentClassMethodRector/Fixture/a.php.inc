<?php

use B as BAlias;

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Fixture;

abstract class A
{
    public function getMe(): A
    {
        return new self();
    }
}

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Fixture;

final class B extends A
{
    public function getMe(): BAlias
    {
        return parent::getMe();
    }
}
?>
