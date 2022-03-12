<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class SomeParent
{
    protected function getValues()
    {
    }
}

class SomeParent2 extends SomeParent
{
    protected function getValues()
    {
    }
}

class ParentChildAllDetected2 extends SomeParent2
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
    protected function getValues()
    {
    }
}

class SomeParent2 extends SomeParent
{
    protected function getValues()
    {
    }
}

class ParentChildAllDetected2 extends SomeParent2
{
    protected function getValues(): void
    {
    }
}

?>
