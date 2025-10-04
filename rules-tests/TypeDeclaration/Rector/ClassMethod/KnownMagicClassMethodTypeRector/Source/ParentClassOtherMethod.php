<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Source;

abstract class ParentClassWithOtherMethod
{
    public function __invoke()
    {
    }
}
