<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector\Fixture;

class A {
    /** @var string */
    public $property;
}

final class B {
    /** @inheritDoc */
    public $property = 'test';
}
?>
