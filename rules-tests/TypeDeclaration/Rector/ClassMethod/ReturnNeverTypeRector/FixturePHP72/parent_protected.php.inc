<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Source\vendor\SomeInterfaceWithReturnType;

final class ParentProtected implements SomeInterfaceWithReturnType
{
    /**
     * @return never
     */
    public function run()
    {
        throw new ShouldNotHappenException();
    }
}
