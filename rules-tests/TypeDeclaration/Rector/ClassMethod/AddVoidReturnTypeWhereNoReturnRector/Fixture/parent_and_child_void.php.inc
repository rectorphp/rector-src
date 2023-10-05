<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

/**
 * Both classes have to be in the same fixture, as the one that needs to be skipped is the parent class
 */
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

/**
 * Both classes have to be in the same fixture, as the one that needs to be skipped is the parent class
 */
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
