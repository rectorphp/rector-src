<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

use Rector\Core\Exception\ShouldNotHappenException;

class SkipExistingVoid
{
    public function run(): void
    {
        throw new ShouldNotHappenException('implement by child');
    }
}
