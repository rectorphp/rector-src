<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeDeclarationFromStrictConstantReturnRector\Fixture;

class SkipNested
{
    public const NAME = 'name';

    public function run()
    {
        if (mt_rand(1, 0)) {
            return self::NAME;
        }
    }
}
