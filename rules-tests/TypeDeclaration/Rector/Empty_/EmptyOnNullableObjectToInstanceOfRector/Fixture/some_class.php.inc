<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Source\AnotherObject;

final class SomeClass
{
    public function run(?AnotherObject $anotherObject)
    {
        if (empty($anotherObject)) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Source\AnotherObject;

final class SomeClass
{
    public function run(?AnotherObject $anotherObject)
    {
        if (!$anotherObject instanceof \Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Source\AnotherObject) {
            return false;
        }

        return true;
    }
}

?>
