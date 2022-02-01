<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector\Fixture;

final class A {
    /** @inheritDoc */
    private $property = 'test';
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector\Fixture;

final class A {
    /** @inheritDoc */
    private string $property = 'test';
}
?>
