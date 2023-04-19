<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

function someFunction(?SomeInstance $someClass)
{
    if ($someClass && $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

function someFunction(?SomeInstance $someClass)
{
    if ($someClass instanceof \Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance && $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

?>
