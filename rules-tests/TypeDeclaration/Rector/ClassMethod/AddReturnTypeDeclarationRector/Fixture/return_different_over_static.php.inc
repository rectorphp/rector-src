<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Fixture;

final class ReturnDifferentOverStatic
{
    public function transform(): string
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Fixture;

final class ReturnDifferentOverStatic
{
    public function transform(): static
    {
    }
}

?>
