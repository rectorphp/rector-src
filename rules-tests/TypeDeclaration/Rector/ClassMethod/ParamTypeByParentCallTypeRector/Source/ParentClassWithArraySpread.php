<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector\Source;

class ParentClassWithArraySpread
{
    public function __construct(string $spreadedItem)
    {
    }
}
