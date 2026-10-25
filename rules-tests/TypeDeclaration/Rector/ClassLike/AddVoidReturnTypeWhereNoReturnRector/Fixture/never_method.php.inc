<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class ExitMethod
{
    protected function getValues()
    {
        exit();
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class ExitMethod
{
    protected function getValues(): void
    {
        exit();
    }
}
?>
