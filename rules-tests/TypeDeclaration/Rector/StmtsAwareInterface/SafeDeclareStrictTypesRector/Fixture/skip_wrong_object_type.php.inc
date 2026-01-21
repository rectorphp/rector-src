<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source\AnotherClass;
use Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source\TypedClass;

function acceptsTypedClass(TypedClass $obj): void
{
}

function callWithWrongType(): void
{
    $obj = new AnotherClass();
    acceptsTypedClass($obj);
}
