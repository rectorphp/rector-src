<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class SkipSilentVoid
{
    public function run()
    {
        try {
            if (rand(0, 1)) {
                return;
            }

            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }
}
