<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function returnsStringOrIntButReturnsFloat(): string|int
{
    return 3.14;
}
