<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class SkipTryCatchFinallyDifferentType
{
    public function run()
    {
        try {
            return 1;
        } catch (\RuntimeException $e) {
            return 2;
        } finally {
            return "A";
        }
    }
}
