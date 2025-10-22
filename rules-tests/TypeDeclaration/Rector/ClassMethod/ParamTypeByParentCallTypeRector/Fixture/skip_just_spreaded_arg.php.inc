<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector\Source\ParentClassWithArraySpread;

final class SkipJustSPreadedArg extends ParentClassWithArraySpread
{
    public function __construct($items)
    {
        parent::__construct(...$items);
    }
}
