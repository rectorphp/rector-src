<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Source\ParentClassWithCall;

final class SkipExistsInParent extends ParentClassWithCall
{
    public function __call($method, $args)
    {
    }
}
