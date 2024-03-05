<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class VoidOnPrivateMethod
{
    private function getValues()
    {
        $value = 1000;
        return;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class VoidOnPrivateMethod
{
    private function getValues(): void
    {
        $value = 1000;
        return;
    }
}

?>
