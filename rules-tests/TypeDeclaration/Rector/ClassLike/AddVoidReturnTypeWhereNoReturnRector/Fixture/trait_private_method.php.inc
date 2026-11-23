<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

trait TraitPrivateMethod
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

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

trait TraitPrivateMethod
{
    private function getValues(): void
    {
        $value = 1000;
        return;
    }
}

?>
