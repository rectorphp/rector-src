<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class SkipConstruct
{
    public function __construct()
    {
        yield;
    }
}
