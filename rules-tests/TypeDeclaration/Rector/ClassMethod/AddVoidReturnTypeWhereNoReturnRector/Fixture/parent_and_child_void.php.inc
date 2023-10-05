<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class ParentAndChildvoid
{
    protected function getValues()
    {
    }
}

final class SomeChildFromVoid extends ParentAndChildvoid
{
    protected function getValues()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class ParentAndChildvoid
{
    protected function getValues()
    {
    }
}

final class SomeChildFromVoid extends ParentAndChildvoid
{
    protected function getValues(): void
    {
    }
}

?>
