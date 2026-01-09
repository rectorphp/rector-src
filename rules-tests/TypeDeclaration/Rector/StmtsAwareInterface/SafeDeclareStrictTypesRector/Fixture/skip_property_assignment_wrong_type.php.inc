<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source\TypedClass;

function assignPropertyWrongType(TypedClass $obj): void
{
    $obj->count = '123';
}
