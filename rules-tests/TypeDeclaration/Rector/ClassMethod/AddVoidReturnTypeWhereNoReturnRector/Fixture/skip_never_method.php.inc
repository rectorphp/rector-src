<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class ExitMethod
{
    protected function getValues()
    {
        exit();
    }
}
