<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Source\SomeParentClass;

class SkipExistsInParentClass extends SomeParentClass
{
    protected $field;

    public function __construct(string $field) {
        $this->field = $field;
    }
}