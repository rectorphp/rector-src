<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

function otherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0,1) && $someClass) {
        return $someClass->someMethod();

    }

    return 'no';
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

function otherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0,1) && $someClass instanceof \Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance) {
        return $someClass->someMethod();

    }

    return 'no';
}

?>
