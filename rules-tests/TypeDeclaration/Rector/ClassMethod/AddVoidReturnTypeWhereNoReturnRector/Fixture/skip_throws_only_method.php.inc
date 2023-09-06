<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class NeverMethod
{
    protected function getValues()
    {
        throw new \Exception('you should not call this method');
    }
}
