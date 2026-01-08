<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function acceptsNamedArgsTyped(string $name, int $age): void
{
}

function callWithWrongNamedArg(): void
{
    acceptsNamedArgsTyped(age: '25', name: 'John');
}
