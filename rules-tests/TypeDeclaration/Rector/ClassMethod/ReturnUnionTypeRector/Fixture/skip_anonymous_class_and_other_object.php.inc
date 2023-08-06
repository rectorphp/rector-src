<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class SkipAnonymousClassAndOtherObject
{
    public function run()
    {
        if (rand(0,1)) {
            return new SkipAnonymousClassAndOtherObject();
        }

        return new class {};
    }
}
