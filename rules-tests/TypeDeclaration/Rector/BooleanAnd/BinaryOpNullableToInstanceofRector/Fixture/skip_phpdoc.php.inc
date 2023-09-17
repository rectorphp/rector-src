<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

/**
 * @param SomeInstance|null $someClass
 */
function binaryOrPhpdoc($someClass)
{
    if ($someClass || $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

