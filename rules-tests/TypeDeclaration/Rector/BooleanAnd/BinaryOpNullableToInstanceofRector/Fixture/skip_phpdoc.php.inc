<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

/**
 * @param SomeInstance|null $someClass
 */
function skipBinaryOrPhpdoc($someClass)
{
    if ($someClass || $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

