<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

$func = function ()  {
    if (rand(0, 1)) {
        yield 1;
    }
};

