<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function acceptsInts(int $a, int $b): void
{
}

function callWithUnpack(): void
{
    $args = [1, 2];
    acceptsInts(...$args);
}
