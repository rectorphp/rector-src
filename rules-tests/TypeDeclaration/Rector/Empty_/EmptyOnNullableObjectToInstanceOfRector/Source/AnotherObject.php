<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Source;

final class AnotherObject
{
    public function equals(AnotherObject $anotherObject)
    {
        return (bool) rand(0, 1);
    }
}
