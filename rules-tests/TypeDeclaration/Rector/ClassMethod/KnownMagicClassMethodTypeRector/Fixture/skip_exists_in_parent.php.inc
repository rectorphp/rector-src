<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

final class SkipExistsInParent extends ParentClass
{
    public function __call($method, $args)
    {
    }
}

?>