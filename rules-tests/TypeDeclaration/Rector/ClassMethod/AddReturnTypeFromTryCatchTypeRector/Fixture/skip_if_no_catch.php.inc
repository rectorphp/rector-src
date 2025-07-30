<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class SkipIfNoCatch
{
    public function run()
    {
        try {
            return 1;
        } finally {
            return 5;
        }
    }
}
