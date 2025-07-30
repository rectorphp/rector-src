<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\Fixture;

final class SkipKnownType
{
    public function run(): int
    {
        try {

            return 1;

        } catch (\Exception $e) {

            return 2;

        }
    }
}

