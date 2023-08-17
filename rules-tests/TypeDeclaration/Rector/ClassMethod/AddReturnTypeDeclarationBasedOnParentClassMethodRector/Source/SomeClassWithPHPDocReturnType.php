<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source;

class SomeClassWithPHPDocReturnType
{
    /**
     * @return int
     */
    public function run()
    {
      return 5;
    }
}
