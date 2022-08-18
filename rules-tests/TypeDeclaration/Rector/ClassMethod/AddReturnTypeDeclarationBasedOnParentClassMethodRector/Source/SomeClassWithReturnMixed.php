<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source;

class SomeClassWithReturnMixed
{
    public function run(): mixed
    {
      return 5;
    }
}
