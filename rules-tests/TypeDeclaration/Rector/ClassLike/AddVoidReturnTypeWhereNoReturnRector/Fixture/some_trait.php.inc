<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

trait AuthUserTrait
{
    public function getValues()
    {
        $value = 1000;
        return;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

trait AuthUserTrait
{
    public function getValues(): void
    {
        $value = 1000;
        return;
    }
}

?>
