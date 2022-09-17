<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector\Fixture;

class SkipReturnDeclarationExists
{
    public function run()
    {
        fn (): array => [];
    }
}
