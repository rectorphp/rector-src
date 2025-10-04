<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Source;

abstract class ParentClassWithCall
{
    public function __call($method, $args)
    {
    }
}