<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

function binaryOr(?SomeInstance $someClass)
{
    if ($someClass || $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

?>
