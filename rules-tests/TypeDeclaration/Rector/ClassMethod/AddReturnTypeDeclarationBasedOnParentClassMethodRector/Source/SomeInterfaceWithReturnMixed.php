<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source;

interface SomeInterfaceWithReturnMixed
{
    public function run(): mixed;
}
