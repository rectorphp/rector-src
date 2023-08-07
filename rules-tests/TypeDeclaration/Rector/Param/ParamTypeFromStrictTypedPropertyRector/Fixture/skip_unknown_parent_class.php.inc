<?php

namespace Rector\Tests\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector\Fixture;

final class SkipUnknownParentClass extends UnknownParentClass
{
    private array $items = [];

    public function redirect(string $path, $args = [])
    {
        $this->items = $args;
    }
}
