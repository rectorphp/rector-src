<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector\Fixture;

class A {
    public $property;
}

final class B extends A {
    /** @inheritDoc */
    public $property = 'test';
}
?>
