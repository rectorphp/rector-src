<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class SkipBareMixed
{
    public function run(mixed $mixed): array
    {
        return [$mixed];
    }
}
