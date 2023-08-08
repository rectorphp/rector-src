<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

final class SkipAlreadyTypedNonVoidOrNever
{
    public function run(): bool
    {
        return true;
    }
}
