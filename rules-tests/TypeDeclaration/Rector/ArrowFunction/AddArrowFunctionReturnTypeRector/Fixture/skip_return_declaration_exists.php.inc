<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class SkipReturnDeclarationExists
{
    public function run()
    {
        fn (): array => [];
    }
}
