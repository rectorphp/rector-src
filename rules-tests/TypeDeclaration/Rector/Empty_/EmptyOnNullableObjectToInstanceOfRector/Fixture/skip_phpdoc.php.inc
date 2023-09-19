<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Source\AnotherObject;

final class SkipPhpdoc
{
    /**
     * @param ?AnotherObject $anotherObject
     */
    public function run($anotherObject)
    {
        if (! empty($anotherObject)) {
            return false;
        }

        return true;
    }
}
