<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector\Source;

abstract class ParentMethodWithUnion
{
    final public function getById(int|string $id)
    {
    }
}
