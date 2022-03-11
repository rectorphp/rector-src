<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class NonFinalProtectedMethod
{
    protected function getValues()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class NonFinalProtectedMethod
{
    protected function getValues(): void
    {
    }
}

?>
