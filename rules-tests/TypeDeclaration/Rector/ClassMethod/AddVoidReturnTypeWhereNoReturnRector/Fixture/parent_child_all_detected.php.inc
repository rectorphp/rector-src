<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class SomeParent
{
    protected function getValues()
    {
    }
}

class ParentChildAllDetected extends SomeParent
{
    protected function getValues()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class SomeParent
{
    protected function getValues(): void
    {
    }
}

class ParentChildAllDetected extends SomeParent
{
    protected function getValues(): void
    {
    }
}

?>
