<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

class SkipMixed
{
    public function get($id)
    {
        if (rand(0,1)) {
            return [];
        }

        // do nothing
    }
}
