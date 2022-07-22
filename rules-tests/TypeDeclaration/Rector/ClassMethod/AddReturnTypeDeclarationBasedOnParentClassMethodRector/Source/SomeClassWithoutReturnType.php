<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source;

class SomeClassWithoutReturnType
{
    public function run()
    {
      return 5;
    }
}
