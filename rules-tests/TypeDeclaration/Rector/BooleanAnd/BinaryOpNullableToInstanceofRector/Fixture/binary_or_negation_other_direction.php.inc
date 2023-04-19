<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

function binaryOrNegationOtherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0, 100) || ! $someClass) {
        return 'yes';
    }

    return 'no';
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

function binaryOrNegationOtherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0, 100) || ! $someClass instanceof \Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance) {
        return 'yes';
    }

    return 'no';
}

?>
