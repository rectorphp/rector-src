<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class SkipReturnEmpty
{
    public function run(): array
    {
        return [];
    }
}
